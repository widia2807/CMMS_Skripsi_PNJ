<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('assets', function (Blueprint $table) {
        $table->unsignedInteger('quantity')->default(1)->after('sub_category_id');
    });
}

public function down()
{
    Schema::table('assets', function (Blueprint $table) {
        $table->dropColumn('quantity');
    });
}
};
