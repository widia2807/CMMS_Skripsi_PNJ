<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('repair_requests', function (Blueprint $table) {
        $table->foreignId('sub_category_id')->nullable()->after('category_id');
    });
}

public function down()
{
    Schema::table('repair_requests', function (Blueprint $table) {
        $table->dropColumn('sub_category_id');
    });
}
};
