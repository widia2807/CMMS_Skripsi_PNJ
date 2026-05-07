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
    Schema::table('assets', function (Blueprint $table) {
        // hapus kolom lama
        $table->dropColumn('location');

        // tambah relasi ke rooms
        $table->foreignId('room_id')
              ->nullable()
              ->constrained()
              ->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('assets', function (Blueprint $table) {
        $table->string('location')->nullable();
        $table->dropForeign(['room_id']);
        $table->dropColumn('room_id');
    });
}
};
