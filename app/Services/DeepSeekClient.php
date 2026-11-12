<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class DeepSeekClient
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl,
        private readonly string $model,
    ) {}

    /**
     * Send a single-turn user prompt and return the raw response text.
     *
     * @throws RuntimeException on HTTP or parse errors
     */
    public function chat(string $prompt, int $timeoutSeconds = 180): string
    {
        $response = Http::withToken($this->apiKey)
            ->timeout($timeoutSeconds)
            ->withoutVerifying()
            ->post(rtrim($this->baseUrl, '/').'/chat/completions', [
                'model'    => $this->model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if (! $response->successful()) {
            throw new RuntimeException("DeepSeek API error {$response->status()}: {$response->body()}");
        }

        $content = $response->json('choices.0.message.content');

        if (! is_string($content)) {
            throw new RuntimeException('DeepSeek returned an unexpected response format.');
        }

        return $content;
    }
}
