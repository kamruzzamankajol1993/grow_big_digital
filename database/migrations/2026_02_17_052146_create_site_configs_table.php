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
        Schema::create('site_configs', function (Blueprint $table) {
            $table->id();
            // Branding & Logos
            $table->string('site_name');
            $table->string('logo'); 
            $table->string('mobile_version_logo')->nullable();
            
            // Global UI Elements
            $table->string('quick_button_text')->nullable();
            $table->string('book_appointment_button_text')->nullable();
            $table->text('book_appointment_link')->nullable();
            $table->text('icon')->nullable(); 
            
            // Contact Details
            $table->string('email');
            $table->string('phone');
            $table->string('whatsapp_number')->nullable();
            $table->text('address');
            
            // Footer Content
            $table->text('footer_short_description');

            // Essential SEO Columns
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->text('google_analytics_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_configs');
    }
};
