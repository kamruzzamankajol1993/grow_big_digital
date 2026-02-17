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
        Schema::create('grow_big_quick_audits', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
        $table->string('service'); // e.g., SEO, Social Media Audit
        $table->string('profile_or_social_url'); 
        $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grow_big_quick_audits');
    }
};
