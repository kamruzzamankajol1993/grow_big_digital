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
        Schema::table('site_configs', function (Blueprint $table) {
            // Branding সেকশনের পর বা quick_button_text এর পরে কলামগুলো যুক্ত করছি
            $table->string('book_appointment_button_text')->nullable()->after('quick_button_text');
            $table->string('book_appointment_link')->nullable()->after('book_appointment_button_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_configs', function (Blueprint $table) {
            $table->dropColumn(['book_appointment_button_text', 'book_appointment_link']);
        });
    }
};