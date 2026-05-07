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
    if (!Schema::hasColumn('repair_requests', 'company_id')) {
        Schema::table('repair_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
        });
    }

    if (!Schema::hasColumn('categories', 'company_id')) {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
        });
    }

    if (!Schema::hasColumn('sub_categories', 'company_id')) {
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
        });
    }

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('all_tables', function (Blueprint $table) {
            //
        });
    }
};
