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
        Schema::create('requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id'); // PIC
    $table->string('title');
    $table->text('description');
    $table->string('status')->default('pending'); 
    $table->timestamps();
    $table->text('reject_reason')->nullable();
    $table->timestamp('approved_at')->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
