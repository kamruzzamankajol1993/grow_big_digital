<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('brands', function (Blueprint $table) {
        // category_id যুক্ত করা হলো, যা null হতে পারে
        $table->foreignId('category_id')->nullable()->after('id')->constrained('categories')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('brands', function (Blueprint $table) {
        $table->dropForeign(['category_id']);
        $table->dropColumn('category_id');
    });
}
};
