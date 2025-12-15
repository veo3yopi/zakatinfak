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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->boolean('program_hero_show_title')->default(true)->after('program_banner_url');
            $table->boolean('program_hero_show_summary')->default(true)->after('program_hero_show_title');
            $table->boolean('program_hero_show_cta')->default(true)->after('program_hero_show_summary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'program_hero_show_title',
                'program_hero_show_summary',
                'program_hero_show_cta',
            ]);
        });
    }
};
