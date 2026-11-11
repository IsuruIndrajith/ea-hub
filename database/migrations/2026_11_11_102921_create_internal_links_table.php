<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_links', function (Blueprint $table) {
            $table->id();
            $table->morphs('linkable'); // linkable_type + linkable_id + index
            $table->string('url');
            $table->string('anchor_text')->nullable();
            $table->string('location')->nullable(); // intro|verdict|faq
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_links');
    }
};