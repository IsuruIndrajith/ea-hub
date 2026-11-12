<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('eas', function (Blueprint $table) {
            $table->string('developer_profile_link')->nullable()->after('developer_name');
        });
    }

    public function down(): void
    {
        Schema::table('eas', function (Blueprint $table) {
            $table->dropColumn('developer_profile_link');
        });
    }
};
