<?php

namespace App\Providers;

use App\Services\DeepSeekClient;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DeepSeekClient::class, fn () => new DeepSeekClient(
            apiKey:  config('services.deepseek.api_key', ''),
            baseUrl: config('services.deepseek.base_url', 'https://api.deepseek.com'),
            model:   config('services.deepseek.model', 'deepseek-chat'),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if (! app()->runningInConsole()) {
            set_time_limit(300);
        }
    }
}
