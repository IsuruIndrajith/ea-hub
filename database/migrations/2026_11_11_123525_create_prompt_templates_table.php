<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prompt_templates', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);              // product_page | set_review
            $table->string('name', 100);             // e.g. "Master Prompt"
            $table->longText('body');                // full prompt text
            $table->string('version')->default('1.0');
            $table->boolean('is_active')->default(true);

            $table->index(['type', 'name']);
            $table->index(['type', 'name', 'is_active']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prompt_templates');
    }
};