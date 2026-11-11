<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable'); // imageable_type + imageable_id + index
            $table->string('filename')->nullable();
            $table->string('url');
            $table->string('alt_text')->nullable();
            $table->string('caption')->nullable();
            $table->string('section')->nullable();   // input|overview|profile|loading|customized
            $table->string('placement')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};