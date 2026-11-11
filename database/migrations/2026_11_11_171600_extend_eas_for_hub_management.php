<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eas', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique();
            $table->string('vendor_url')->nullable();
            $table->json('primary_keywords')->nullable();
            $table->json('secondary_keywords')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
        });

        DB::table('eas')->orderBy('id')->get()->each(function (object $ea): void {
            $slugBase = Str::slug(trim(($ea->name ?? '').' '.($ea->version ?? '')));
            $slugBase = $slugBase !== '' ? $slugBase : 'ea-'.$ea->id;
            $slug = $slugBase;
            $suffix = 2;

            while (DB::table('eas')->where('slug', $slug)->where('id', '!=', $ea->id)->exists()) {
                $slug = $slugBase.'-'.$suffix++;
            }

            DB::table('eas')->where('id', $ea->id)->update([
                'slug' => $slug,
                'vendor_url' => $ea->vendor_link,
                'primary_keywords' => json_encode([]),
            ]);
        });

        Schema::table('eas', function (Blueprint $table) {
            $table->unique(['name', 'version']);
        });

        Schema::create('ea_signals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ea_id')->constrained('eas')->cascadeOnDelete();
            $table->string('type');
            $table->string('url');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['ea_id', 'type']);
        });

        DB::table('eas')->whereNotNull('myfxbook_link')->orderBy('id')->each(function (object $ea): void {
            DB::table('ea_signals')->insert([
                'ea_id' => $ea->id,
                'type' => 'myfxbook',
                'url' => $ea->myfxbook_link,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ea_signals');

        Schema::table('eas', function (Blueprint $table) {
            $table->dropUnique(['name', 'version']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn([
                'slug', 'vendor_url', 'primary_keywords', 'secondary_keywords',
                'created_by', 'updated_by', 'deleted_at',
            ]);
        });
    }
};
