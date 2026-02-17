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
        Schema::create('grow_big_heroes', function (Blueprint $table) {
            $table->id();
            // Main Content
        $table->string('main_title');
        $table->string('subtitle');
        $table->string('button_name_one')->nullable();
        $table->string('button_name_two')->nullable();

        // Featured Member One
        $table->string('member_one_image')->nullable();
        $table->string('member_one_name')->nullable();
        $table->string('member_one_designation')->nullable();
        $table->string('member_one_icon')->nullable();

        // Featured Member Two
        $table->string('member_two_image')->nullable();
        $table->string('member_two_name')->nullable();
        $table->string('member_two_designation')->nullable();
        $table->string('member_two_icon')->nullable();

        // Featured Member Three
        $table->string('member_three_image')->nullable();
        $table->string('member_three_name')->nullable();
        $table->string('member_three_designation')->nullable();
        $table->string('member_three_icon')->nullable();

        // Success Stats
        $table->string('success_count')->nullable();
        $table->string('success_text')->nullable();
        $table->string('success_icon')->nullable();

        // Client Stats
        $table->string('client_count')->nullable();
        $table->string('client_text')->nullable();
        $table->string('client_icon')->nullable();

        // Positive Stats
        $table->string('positive_count')->nullable();
        $table->string('positive_text')->nullable();
        $table->string('positive_icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grow_big_heroes');
    }
};
