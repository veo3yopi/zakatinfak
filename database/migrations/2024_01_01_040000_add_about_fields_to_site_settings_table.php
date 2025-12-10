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
            $table->string('about_hero_url')->nullable()->after('favicon_url');
            $table->text('about_summary')->nullable()->after('about_subtitle');
            $table->text('about_principles')->nullable()->after('about_values');
            $table->text('about_goals')->nullable()->after('about_principles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['about_hero_url', 'about_summary', 'about_principles', 'about_goals']);
        });
    }
};
