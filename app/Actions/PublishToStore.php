<?php

namespace App\Actions;

use App\Models\Ea;
use App\Models\ProductPage;
use App\Services\EaStoreClient;
use RuntimeException;

class PublishToStore
{
    public function __construct(private readonly EaStoreClient $client) {}

    /**
     * Build the sync payload from an EA's AI-generated content and push it to the store.
     * Generated content is the sole source of truth; images are the only manually-supplied data.
     *
     * @param  array{price_cents: int, sale_price_cents: int, hue?: int, is_active?: bool}  $overrides
     * @return array<string, mixed>  The store's response data.
     *
     * @throws RuntimeException
     */
    public function run(Ea $ea, array $overrides): array
    {
        $ea->loadMissing([
            'generatedProductPage',
            'generatedSetReview',
            'productPage.images',
            'setReview.images',
        ]);

        // Auto-create a minimal ProductPage stub so the image upload relation always exists.
        $productPage = $ea->productPage
            ?? ProductPage::firstOrCreate(['ea_id' => $ea->id]);
        $ea->setRelation('productPage', $productPage);

        // AI-generated content (source of truth)
        $pc  = $ea->generatedProductPage?->content ?? [];   // product_page
        $src = $ea->generatedSetReview?->content ?? [];      // set_review raw

        // Set review content may be nested under a 'content' key
        $ct  = $src['content'] ?? $src;
        $seo = $src['seo'] ?? [];

        $payload = [
            'slug'             => $ea->slug,
            'name'             => $ea->name,
            'version'          => $ea->version ?? 'v1.0',
            'platform'         => $ea->platform ?? 'MT5',
            'market'           => $this->resolveMarket($ea),
            'categories'       => $this->resolveCategories($ea),
            'price_cents'      => $overrides['price_cents'],
            'sale_price_cents' => $overrides['sale_price_cents'],
            'hue'              => $overrides['hue'] ?? 0,
            'growth'           => $this->parseGrowth($ea->myfxbook_gain),
            'drawdown'         => 0,
            'description'      => $pc['short_description'] ?? '',
            'meta_title'       => $pc['rank_math_title']
                ?? $seo['recommended_meta_title']
                ?? $ea->name,
            'meta_description' => $pc['rank_math_description']
                ?? $seo['meta_description']
                ?? '',
            'features'         => $this->extractFeaturesFromGenerated($pc),
            'vendor_url'       => $ea->vendor_url ?? '',
            'myfxbook_link'    => $ea->myfxbook_link ?? '',
            'is_active'        => $overrides['is_active'] ?? true,
            'extended'         => $this->buildExtendedFromGenerated($pc, $productPage, $ea),
        ];

        return $this->client->syncProduct($payload);
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    private function resolveMarket(Ea $ea): string
    {
        $pairs = $ea->supported_pairs ?? [];

        return ! empty($pairs) ? $pairs[0] : 'All pairs';
    }

    private function resolveCategories(Ea $ea): array
    {
        $cats  = [];
        $pairs = array_map('strtoupper', $ea->supported_pairs ?? []);

        if (array_intersect($pairs, ['XAUUSD', 'GOLD'])) {
            $cats[] = 'gold-ea';
        }
        if ($ea->platform && str_contains(strtoupper($ea->platform), 'MT')) {
            $cats[] = 'forex-ea';
        }

        return array_values(array_unique($cats)) ?: ['forex-ea'];
    }

    private function parseGrowth(?string $gain): float
    {
        if (! $gain) {
            return 0.0;
        }

        return (float) str_replace(['%', ','], ['', '.'], $gain);
    }

    /** Key features come from tg_message.key_features in the generated product page. */
    private function extractFeaturesFromGenerated(array $content): array
    {
        $tg = $content['tg_message'] ?? null;

        if (is_array($tg) && ! empty($tg['key_features'])) {
            return collect($tg['key_features'])
                ->filter(fn ($f) => is_string($f) && $f !== '')
                ->take(5)
                ->values()
                ->all();
        }

        return [];
    }

    private function buildExtendedFromGenerated(array $content, mixed $productPage, Ea $ea): array
    {
        // Manually-uploaded images from the ProductPage relation
        $pageImages = $productPage?->images
            ?->map(fn ($i) => [
                'url'      => $i->url,
                'alt_text' => $i->alt_text,
                'caption'  => $i->caption,
                'section'  => $i->section,
            ])->all() ?? [];

        // Images from EA's Spatie MediaLibrary collections (live signals, myfxbook, other)
        $mediaImages = $ea->getMedia('live_signal_images')
            ->merge($ea->getMedia('myfxbook_review_images'))
            ->merge($ea->getMedia('other_result_images'))
            ->map(fn ($m) => [
                'url'      => $m->getUrl(),
                'alt_text' => $m->name,
                'caption'  => null,
                'section'  => 'overview',
            ])->all();

        // Extract telegram support line from tg_message
        $tg     = $content['tg_message'] ?? null;
        $tgLine = is_string($tg) ? $tg : ($tg['description'] ?? null);

        return [
            'description_md'        => $content['description'] ?? null,
            'short_description'     => $content['short_description'] ?? null,
            'faqs'                  => $this->normalizeFaqs($content['faq'] ?? []),
            'telegram_support_line' => $tgLine,
            'images'                => array_merge($pageImages, $mediaImages),
        ];
    }

    private function buildSetReviewFromGenerated(array $ct, array $seo, Ea $ea): array
    {
        // Set files from AI-generated set_profiles[]
        $setFiles = collect($ct['set_profiles'] ?? [])
            ->map(fn ($p) => [
                'name'    => $p['file_name'] ?? '',
                'body_md' => $p['body'] ?? null,
            ])
            ->filter(fn ($sf) => ! empty($sf['name']))
            ->values()
            ->all();

        // Manually-uploaded images from the SetReview relation
        $reviewImages = $ea->setReview?->images
            ?->map(fn ($i) => [
                'url'      => $i->url,
                'alt_text' => $i->alt_text,
                'caption'  => $i->caption,
                'section'  => $i->section,
            ])->all() ?? [];

        // set_file_images from EA's Spatie MediaLibrary
        $setFileMedia = $ea->getMedia('set_file_images')
            ->map(fn ($m) => [
                'url'      => $m->getUrl(),
                'alt_text' => $m->name,
                'caption'  => null,
                'section'  => 'set_file',
            ])->all();

        return [
            'focus_keyword'                => $seo['focus_keyword'] ?? null,
            'recommended_slug'             => $seo['recommended_slug'] ?? null,
            'suggested_h1'                 => $seo['suggested_h1'] ?? null,
            'meta_description'             => $seo['meta_description'] ?? null,
            'seo_introduction'             => $ct['seo_introduction'] ?? null,
            'specs_table'                  => $ct['specs_table'] ?? null,
            'set_comparison_table'         => $ct['set_comparison_table'] ?? null,
            'requirements_table'           => $ct['requirements_table'] ?? null,
            'customization_guidance'       => $ct['customization_guidance'] ?? null,
            'customization_cautions'       => $ct['customization_cautions'] ?? null,
            'config_strengths_limitations' => $ct['config_strengths_limitations'] ?? null,
            'who_should_use'               => $ct['who_should_use'] ?? null,
            'final_verdict'                => $ct['final_verdict'] ?? null,
            'product_page_cta'             => $ct['product_page_cta'] ?? null,
            'review_methodology'           => $ct['review_methodology'] ?? null,
            'risk_disclaimer'              => $ct['risk_disclaimer'] ?? null,
            'load_set_steps'               => $this->normalizeLoadSetSteps($ct['load_set_steps'] ?? null),
            'detailed_parameter_analysis'  => $ct['detailed_parameter_analysis'] ?? null,
            'set_files'                    => $setFiles,
            'faqs'                         => $this->normalizeFaqs($ct['faq'] ?? []),
            'images'                       => array_merge($reviewImages, $setFileMedia),
        ];
    }

    /**
     * Convert load_set_steps from {mt4, mt5} object format to [{platform, steps}] array.
     * Generated content stores it as {mt4: "markdown...", mt5: "markdown..."}.
     */
    private function normalizeLoadSetSteps(mixed $steps): ?array
    {
        if (empty($steps)) {
            return null;
        }

        // Already an array of steps (not mt4/mt5 keyed object)
        if (is_array($steps) && ! isset($steps['mt4']) && ! isset($steps['mt5'])) {
            return array_values($steps);
        }

        // Convert {mt4: "...", mt5: "..."} to [{platform, steps}]
        if (is_array($steps)) {
            $result = [];
            if (! empty($steps['mt4'])) {
                $result[] = ['platform' => 'MT4', 'steps' => $steps['mt4']];
            }
            if (! empty($steps['mt5'])) {
                $result[] = ['platform' => 'MT5', 'steps' => $steps['mt5']];
            }

            return $result ?: null;
        }

        return null;
    }

    private function normalizeFaqs(array $faqs): array
    {
        return collect($faqs)
            ->map(fn ($f) => [
                'question' => $f['question'] ?? '',
                'answer'   => $f['answer'] ?? '',
            ])
            ->filter(fn ($f) => ! empty($f['question']))
            ->values()
            ->all();
    }
}
