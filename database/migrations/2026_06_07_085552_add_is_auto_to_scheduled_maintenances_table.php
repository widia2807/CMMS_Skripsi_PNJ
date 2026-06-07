<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::table('scheduled_maintenances', function (Blueprint $table) {
        $table->boolean('is_auto')->default(false)->after('company_id');
    });
}

public function down()
{
    Schema::table('scheduled_maintenances', function (Blueprint $table) {
        $table->dropColumn('is_auto');
    });
}
};
