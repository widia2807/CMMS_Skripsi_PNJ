<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::table('repair_requests', function (Blueprint $table) {
            $table->string('status')->change();
            $table->text('inspection_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('repair_requests', function (Blueprint $table) {
        $table->dropColumn('schedule_date');
    });
    }
};
