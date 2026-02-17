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
        Schema::create('grow_big_teams', function (Blueprint $table) {
            $table->id();
            $table->string('image');
        $table->string('name');
        $table->string('designation');
        $table->json('skills'); // Stores array data like ["SEO", "Copywriting"]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grow_big_teams');
    }
};
