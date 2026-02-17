<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::table('grow_big_contacts', function (Blueprint $table) {
        $table->integer('is_read')->default(0)->comment('0=Unread, 1=Read');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grow_big_contacts', function (Blueprint $table) {
            //
        });
    }
};
