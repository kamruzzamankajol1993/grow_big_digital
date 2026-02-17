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
        Schema::create('who_we_are_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('who_we_are_id')->constrained('grow_big_who_we_ares')->onDelete('cascade');
        $table->string('icon'); // For class names or SVG paths
        $table->string('title');
        $table->text('short_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('who_we_are_lists');
    }
};
