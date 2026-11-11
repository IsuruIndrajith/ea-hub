<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ea_id')->unique()->constrained('eas')->cascadeOnDelete();

            // Input fields
            $table->json('input_keywords')->nullable();
            $table->longText('input_contents')->nullable();
            $table->string('reviewed_by_author_name')->nullable();
            $table->string('reviewed_by_author_profile_link')->nullable();

            // SEO block
            $table->string('focus_keyword')->nullable();
            $table->json('secondary_keywords')->nullable();
            $table->string('meta_title_1')->nullable();
            $table->string('meta_title_2')->nullable();
            $table->string('meta_title_3')->nullable();
            $table->string('meta_description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('rank_math_focus_keyword')->nullable();

            // Full Product Description sections
            $table->longText('intro')->nullable();
            $table->longText('first_hand_test_results')->nullable();
            $table->json('key_features')->nullable();
            $table->json('why_buy_this')->nullable();
            $table->text('honest_limitation')->nullable();
            $table->text('why_different')->nullable();
            $table->json('strategy_logic')->nullable();
            $table->json('risk_controls')->nullable();
            $table->text('risk_disclaimer_line')->nullable();
            $table->json('optimized_settings_table')->nullable();
            $table->json('install_steps')->nullable();
            $table->text('author_info')->nullable();
            $table->text('is_it_worth_it')->nullable();
            $table->text('telegram_support_line')->nullable();
            $table->text('reviewed_by_closer')->nullable();
            $table->text('related_categories_paragraph')->nullable();

            // CSV packaging fields
            $table->string('csv_name')->nullable();
            $table->string('csv_published')->default('-1');
            $table->text('csv_short_description')->nullable();
            $table->longText('csv_description')->nullable();
            $table->text('csv_meta_tg_message')->nullable();
            $table->longText('csv_meta_faq')->nullable();

            // Workflow status
            $table->string('status')->default('draft'); // draft|generated|published

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_pages');
    }
};