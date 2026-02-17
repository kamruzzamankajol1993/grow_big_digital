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
        Schema::create('contact_headers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
        $table->string('subtitle_one')->nullable();
        $table->string('subtitle_two')->nullable();
        $table->text('short_description')->nullable();
          $table->string('button_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_headers');
    }
};
