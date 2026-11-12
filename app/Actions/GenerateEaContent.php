<?php

namespace App\Actions;

use App\Models\Ea;
use App\Models\GeneratedContent;
use App\Models\PromptTemplate;
use App\Services\DeepSeekClient;
use RuntimeException;

class GenerateEaContent
{
    public function __construct(private readonly DeepSeekClient $client) {}

    /**
     * Build the prompt, call DeepSeek, parse JSON, upsert GeneratedContent.
     *
     * @throws RuntimeException|\JsonException
     */
    public function run(Ea $ea, string $type): GeneratedContent
    {
        $template = PromptTemplate::activeFor($type);

        if (! $template) {
            throw new RuntimeException("No active prompt template found for type: {$type}.");
        }

        $prompt  = $this->substitute($template->body, $ea);
        $rawJson = $this->client->chat($prompt);

        // Strip markdown fences if the model wraps the response despite instructions.
        $rawJson = preg_replace('/^```(?:json)?\s*/i', '', trim($rawJson));
        $rawJson = preg_replace('/\s*```$/', '', $rawJson);

        $decoded = json_decode($rawJson, true, 512, JSON_THROW_ON_ERROR);

        return GeneratedContent::updateOrCreate(
            ['ea_id' => $ea->id, 'type' => $type],
            ['content' => $decoded],
        );
    }

    private function substitute(string $template, Ea $ea): string
    {
        $ea->loadMissing(['signals', 'setReview']);

        $myfxbookSignals = $ea->signals
            ->where('type', 'myfxbook')
            ->map(fn ($s) => 'URL: '.$s->url.($s->notes ? ' | Notes: '.$s->notes : ''))
            ->join("\n");

        $mql5Signals = $ea->signals
            ->where('type', 'mql5')
            ->map(fn ($s) => 'URL: '.$s->url.($s->notes ? ' | Notes: '.$s->notes : ''))
            ->join("\n");

        $suppliedImages = $ea->getMedia('live_signal_images')
            ->merge($ea->getMedia('myfxbook_review_images'))
            ->merge($ea->getMedia('other_result_images'))
            ->map(fn ($m) => $m->getUrl())
            ->join("\n");

        $setFileScreenshots = $ea->getMedia('set_file_images')
            ->map(fn ($m) => $m->getUrl())
            ->join("\n");

        $setFileNames = $ea->getMedia('set_file_images')
            ->map(fn ($m) => $m->file_name)
            ->join(', ');

        $replacements = [
            '{{ea_name}}'                => $ea->name,
            '{{primary_keywords}}'       => implode(', ', $ea->primary_keywords ?? []),
            '{{secondary_keywords}}'     => implode(', ', $ea->secondary_keywords ?? []),
            '{{vendor_url}}'             => $ea->vendor_url ?? '',
            '{{developer_name}}'         => $ea->developer_name ?? '',
            '{{developer_profile_link}}' => $ea->developer_profile_link ?? '',
            '{{developer_background}}'   => $ea->developer_background ?? '',
            '{{platform}}'               => $ea->platform ?? '',
            '{{product_page_url}}'       => $ea->product_page_url ?? '',
            '{{myfxbook_signals}}'       => $myfxbookSignals,
            '{{mql5_signals}}'           => $mql5Signals,
            '{{supplied_images}}'        => $suppliedImages,
            '{{supplied_contents}}'      => '',
            '{{set_file_screenshots}}'   => $setFileScreenshots,
            '{{set_file_names}}'         => $setFileNames,
            '{{set_file_data}}'          => '',
            '{{vendor_docs}}'            => $ea->setReview?->vendor_docs ?? '',
            '{{config_notes}}'           => $ea->setReview?->config_notes ?? '',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }
}
