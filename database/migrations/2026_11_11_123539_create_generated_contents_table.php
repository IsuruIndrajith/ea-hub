<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generated_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ea_id')->constrained('eas')->cascadeOnDelete();
            $table->string('type');   // product_page | set_review
            $table->json('content');

            // one latest generation per EA per type
            $table->unique(['ea_id', 'type']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_contents');
    }
};