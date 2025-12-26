<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('payment_channel', 32)->nullable()->after('payment_method');
            $table->unsignedBigInteger('admin_fee_amount')->nullable()->after('payment_channel');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['payment_channel', 'admin_fee_amount']);
        });
    }
};
