<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('footer_about_text')->nullable()->after('program_show_categories');
            $table->json('footer_links')->nullable()->after('footer_about_text');
            $table->json('footer_social_links')->nullable()->after('footer_links');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['footer_about_text', 'footer_links', 'footer_social_links']);
        });
    }
};
