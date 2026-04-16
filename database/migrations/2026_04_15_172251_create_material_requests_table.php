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
       Schema::create('material_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('repair_request_id');
    $table->string('item_name');
    $table->integer('qty');
    $table->string('status')->default('pending'); // pending / approved
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_requests');
    }
};
