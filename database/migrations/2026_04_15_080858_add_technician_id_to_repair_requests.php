<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    if (Schema::hasTable('repair_requests')) {
        Schema::table('repair_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('repair_requests', 'technician_id')) {
                $table->unsignedBigInteger('technician_id')->nullable();
            }
        });
    }
}

    public function down()
{
    if (Schema::hasTable('repair_requests')) {
        Schema::table('repair_requests', function (Blueprint $table) {
            if (Schema::hasColumn('repair_requests', 'technician_id')) {
                $table->dropColumn('technician_id');
            }
        });
    }
}
};
