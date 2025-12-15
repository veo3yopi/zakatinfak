<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('footer_address')->nullable()->after('footer_social_links');
            $table->string('footer_email')->nullable()->after('footer_address');
            $table->string('footer_phone')->nullable()->after('footer_email');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['footer_address', 'footer_email', 'footer_phone']);
        });
    }
};
