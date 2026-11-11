<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('set_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('set_review_id')->constrained('set_reviews')->cascadeOnDelete();
            $table->string('name');
            $table->text('purpose')->nullable();
            $table->json('values')->nullable();       // raw param/value pairs
            $table->json('groups')->nullable();
            $table->json('risk_params')->nullable();
            $table->text('differences')->nullable();
            $table->text('limitations')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('set_files');
    }
};