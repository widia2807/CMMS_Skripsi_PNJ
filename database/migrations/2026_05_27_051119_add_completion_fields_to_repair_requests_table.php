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
    Schema::table('repair_requests', function (Blueprint $table) {
        $table->text('completion_note')->nullable();
        $table->string('completion_photo')->nullable();
        $table->string('material_used')->nullable();
        $table->timestamp('completed_at')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repair_requests', function (Blueprint $table) {
            //
        });
    }
};
