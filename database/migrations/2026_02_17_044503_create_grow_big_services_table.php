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
        Schema::create('grow_big_services', function (Blueprint $table) {
            $table->id();
            // Self-referencing foreign key for sub-services
        $table->foreignId('parent_id')->nullable()->constrained('grow_big_services')->onDelete('cascade');
        
        $table->text('icon'); // For SVG code or Icon class names
        $table->string('name');
        $table->text('short_description');
        $table->string('status')->default('active'); // e.g., active, inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grow_big_services');
    }
};
