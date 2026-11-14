<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class EaStoreClient
{
    private string $baseUrl;
    private string $token;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.ea_store.url', env('STORE_API_URL', '')), '/');
        $this->token   = config('services.ea_store.token', env('STORE_API_TOKEN', ''));

        if (! $this->baseUrl) {
            throw new RuntimeException('STORE_API_URL is not configured.');
        }
        if (! $this->token) {
            throw new RuntimeException('STORE_API_TOKEN is not configured.');
        }
    }

    /**
     * Push a product payload to the EA Store sync endpoint.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     *
     * @throws RuntimeException
     */
    public function syncProduct(array $payload): array
    {
        $response = Http::withToken($this->token)
            ->acceptJson()
            ->post("{$this->baseUrl}/api/internal/products/sync", $payload);

        if ($response->failed()) {
            $message = $response->json('message') ?? "HTTP {$response->status()} from EA Store.";
            throw new RuntimeException($message);
        }

        return $response->json();
    }

    /**
     * Push a set review payload to the EA Store set-review sync endpoint.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     *
     * @throws RuntimeException
     */
    public function syncSetReview(array $payload): array
    {
        $response = Http::withToken($this->token)
            ->acceptJson()
            ->post("{$this->baseUrl}/api/internal/set-reviews/sync", $payload);

        if ($response->failed()) {
            $message = $response->json('message') ?? "HTTP {$response->status()} from EA Store.";
            throw new RuntimeException($message);
        }

        return $response->json();
    }
}
