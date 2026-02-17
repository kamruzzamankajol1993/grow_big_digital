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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            // Relationship to GrowBigService
        $table->foreignId('service_id')->constrained('grow_big_services')->onDelete('cascade');
        
        $table->string('image')->nullable(); // Local path to image
        $table->string('video')->nullable(); // Local path to video file
        $table->longText('video_link')->nullable(); // YouTube/Vimeo embed or URL
        
        $table->string('title');
        $table->string('subtitle')->nullable();
        $table->longText('description');
        
        // Define if the video is a 'link' or a 'file'
        $table->enum('video_type', ['link', 'file'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
