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
        Schema::create('team_social_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('grow_big_teams')->onDelete('cascade');
        $table->string('title'); // e.g., LinkedIn, Twitter
        $table->string('link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_social_links');
    }
};
