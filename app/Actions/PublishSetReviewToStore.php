<?php

namespace App\Actions;

use App\Models\Ea;
use App\Models\SetReview;
use App\Services\EaStoreClient;
use Illuminate\Support\Str;
use RuntimeException;

class PublishSetReviewToStore
{
    public function __construct(private readonly EaStoreClient $client) {}

    /**
     * Sync the exact AI-generated set review content to the EA Store.
     * The full generated content object is passed through; only structural transforms
     * (set_profiles → set_files, load_set_steps object → array, faq normalization)
     * and manually-uploaded images are applied on top.
     *
     * @return array<string, mixed>  The store's response data.
     *
     * @throws RuntimeException
     */
    public function run(Ea $ea): array
    {
        $ea->loadMissing([
            'generatedSetReview',
            'setReview.images',
            'setReview.setFiles',
        ]);

        $gen = $ea->generatedSetReview;
        if (! $gen) {
            throw new RuntimeException("No generated set review found for \"{$ea->name}\". Generate it first.");
        }

        $src = $gen->content ?? [];
        // Generated content may nest everything under a 'content' key with a sibling 'seo' key,
        // or everything may be flat at the top level.
        $ct  = $src['content'] ?? $src;
        $seo = $src['seo'] ?? [];

        // ── Pass the full generated content as-is ─────────────────────────────
        // Start with everything the AI produced so no field is silently dropped.
        $content = is_array($ct) ? $ct : [];

        // ── Apply structural transforms ────────────────────────────────────────
        // set_profiles[] → set_files[] (prefer manually-managed set files if present)
        $content['set_files'] = $this->resolveSetFiles($ea->setReview, $ct);
        // Remove the raw set_profiles key so the store doesn't get duplicate data
        unset($content['set_profiles']);

        // load_set_steps {mt4, mt5} object → [{platform, steps}] array
        if (isset($content['load_set_steps'])) {
            $content['load_set_steps'] = $this->normalizeLoadSetSteps($content['load_set_steps']);
        }

        // faq[] → normalized [{question, answer}]
        if (isset($content['faq'])) {
            $content['faqs'] = $this->normalizeFaqs($content['faq']);
            unset($content['faq']);
        } elseif (isset($content['faqs'])) {
            $content['faqs'] = $this->normalizeFaqs($content['faqs']);
        }

        // ── Merge manually-uploaded images ─────────────────────────────────────
        $reviewImages = $ea->setReview?->images
            ?->map(fn ($i) => [
                'url'      => $i->url,
                'alt_text' => $i->alt_text,
                'caption'  => $i->caption,
                'section'  => $i->section,
            ])->all() ?? [];

        $setFileMedia = $ea->getMedia('set_file_images')
            ->map(fn ($m) => [
                'url'      => $m->getUrl(),
                'alt_text' => $m->name,
                'caption'  => null,
                'section'  => 'set_file',
            ])->all();

        $content['images'] = array_merge($reviewImages, $setFileMedia);

        // ── Build the slug (required by the sync endpoint) ─────────────────────
        $slug = $seo['recommended_slug'] ?? null;
        if (! $slug) {
            // Fall back to "{ea-slug}-settings-guide"
            $slug = Str::slug($ea->slug . '-settings-guide');
        }

        $payload = [
            'product_slug'     => $ea->slug,
            'slug'             => $slug,
            'h1'               => $seo['suggested_h1'] ?? null,
            'meta_title'       => $seo['recommended_meta_title'] ?? $seo['meta_title'] ?? null,
            'meta_description' => $seo['meta_description'] ?? null,
            'focus_keyword'    => $seo['focus_keyword'] ?? null,
            'is_published'     => false,
            'content'          => $content,
        ];

        return $this->client->syncSetReview($payload);
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    private function resolveSetFiles(?SetReview $setReview, array $ct): array
    {
        // Prefer manually-managed set files from the SetReview relation in EA Hub
        if ($setReview && $setReview->setFiles->isNotEmpty()) {
            return $setReview->setFiles
                ->map(fn ($sf) => ['name' => $sf->name, 'body_md' => $sf->body ?? null])
                ->all();
        }

        // Fall back to AI-generated set_profiles
        return collect($ct['set_profiles'] ?? [])
            ->map(fn ($p) => ['name' => $p['file_name'] ?? '', 'body_md' => $p['body'] ?? null])
            ->filter(fn ($sf) => ! empty($sf['name']))
            ->values()
            ->all();
    }

    private function normalizeLoadSetSteps(mixed $steps): ?array
    {
        if (empty($steps)) {
            return null;
        }

        // Already [{platform, steps}] array
        if (is_array($steps) && ! isset($steps['mt4']) && ! isset($steps['mt5'])) {
            return array_values($steps);
        }

        // Convert {mt4: "...", mt5: "..."} object to [{platform, steps}]
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
            ->map(fn ($f) => ['question' => $f['question'] ?? '', 'answer' => $f['answer'] ?? ''])
            ->filter(fn ($f) => ! empty($f['question']))
            ->values()
            ->all();
    }
}
