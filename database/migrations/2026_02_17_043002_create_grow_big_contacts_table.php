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
        Schema::create('grow_big_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
        $table->string('email');
        $table->string('phone')->nullable();
        $table->string('interested_in'); // e.g., Marketing, Web Dev
        $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grow_big_contacts');
    }
};
