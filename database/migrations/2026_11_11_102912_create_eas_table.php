<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('version')->nullable();
            $table->string('platform')->nullable();          // MT4, MT5, Both
            $table->string('vendor_link')->nullable();
            $table->string('product_page_url')->nullable();
            $table->json('supported_pairs')->nullable();
            $table->string('recommended_timeframe')->nullable();
            $table->string('strategy_type')->nullable();
            $table->string('account_type')->nullable();
            $table->string('min_deposit')->nullable();
            $table->string('recommended_deposit')->nullable();
            $table->string('recommended_leverage')->nullable();
            $table->string('vps_requirement')->nullable();
            $table->string('developer_name')->nullable();
            $table->text('developer_background')->nullable();
            $table->string('myfxbook_link')->nullable();
            $table->string('myfxbook_gain')->nullable();
            $table->string('product_type')->nullable()->default('Expert Advisor');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eas');
    }
};
