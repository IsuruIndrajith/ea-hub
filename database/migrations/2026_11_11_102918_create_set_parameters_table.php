<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('set_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('set_file_id')->constrained('set_files')->cascadeOnDelete();
            $table->string('name');
            $table->string('value')->nullable();
            $table->string('group')->nullable(); // lot|risk|entry|sl_tp|trade_mgmt|grid|filters
            $table->string('classification')->nullable(); // documented|verified|inferred|unknown
            $table->text('explanation')->nullable();
            $table->text('practical_importance')->nullable();
            $table->string('risk_impact')->nullable(); // direct|indirect|none
            $table->json('interacts_with')->nullable();
            $table->string('modifiable')->nullable(); // safe|keep_default|needs_expertise|unclear
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('set_parameters');
    }
};