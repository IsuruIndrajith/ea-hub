<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('set_review_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('set_review_id')->constrained('set_reviews')->cascadeOnDelete();
            $table->text('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('set_review_faqs');
    }
};