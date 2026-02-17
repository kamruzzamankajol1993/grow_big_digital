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
        Schema::create('grow_big_who_we_ares', function (Blueprint $table) {
            $table->id();
            $table->string('image');
        $table->string('title');
        $table->string('button_name')->nullable();
        $table->string('subtitle_one')->nullable();
        $table->string('subtitle_two')->nullable();
        $table->string('subtitle_three')->nullable();
        $table->text('short_description');
        $table->string('edit_count_text')->nullable(); // e.g., "150+ Projects"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grow_big_who_we_ares');
    }
};
