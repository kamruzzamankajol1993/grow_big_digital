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
        Schema::create('grow_big_testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('image'); // Stores the file path/URL
        $table->string('name');
        $table->string('designation'); // e.g., CEO at TechCorp
        $table->text('short_description'); 
        $table->string('link')->nullable(); // Optional external link
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grow_big_testimonials');
    }
};
