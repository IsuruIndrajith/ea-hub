<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('set_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ea_id')->unique()->constrained('eas')->cascadeOnDelete();

            // Input fields
            $table->text('vendor_docs')->nullable();
            $table->text('config_notes')->nullable();

            // SEO block (independent from product page)
            $table->string('focus_keyword')->nullable();
            $table->json('secondary_keywords')->nullable();
            $table->string('search_intent')->nullable();
            $table->string('meta_title_1')->nullable();
            $table->string('meta_title_2')->nullable();
            $table->string('meta_title_3')->nullable();
            $table->string('recommended_meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('recommended_slug')->nullable();
            $table->string('suggested_h1')->nullable();

            // Body sections
            $table->longText('seo_introduction')->nullable();
            $table->json('specs_table')->nullable();
            $table->json('set_comparison_table')->nullable();
            $table->json('parameter_classification_summary')->nullable();
            $table->json('important_parameters')->nullable();
            $table->json('requirements_table')->nullable();
            $table->json('load_set_steps')->nullable();
            $table->json('customization_guidance')->nullable();
            $table->json('customization_cautions')->nullable();
            $table->longText('config_strengths_limitations')->nullable();
            $table->json('who_should_use')->nullable();
            $table->longText('final_verdict')->nullable();
            $table->text('product_page_cta')->nullable();
            $table->longText('review_methodology')->nullable();
            $table->longText('risk_disclaimer')->nullable();

            // Private editorial notes (not published)
            $table->longText('editorial_notes')->nullable();

            $table->string('status')->default('draft'); // draft|generated|published

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('set_reviews');
    }
};