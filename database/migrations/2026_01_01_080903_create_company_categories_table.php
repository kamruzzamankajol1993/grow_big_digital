<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('company_categories', function (Blueprint $table) {
            $table->id();
            // company_id যা brands টেবিলকে রেফার করবে
            $table->foreignId('company_id')->constrained('brands')->onDelete('cascade');
            
            // ক্যাটাগরি টেবিলের মতো কলাম
            $table->string('name');
            $table->string('slug');
            $table->string('image')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(1); // 1=Active, 0=Inactive
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_categories');
    }
};