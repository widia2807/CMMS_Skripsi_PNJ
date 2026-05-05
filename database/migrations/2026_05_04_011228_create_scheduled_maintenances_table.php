<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheduled_maintenances', function (Blueprint $table) {
            $table->id();

            // Info pekerjaan
            $table->string('title');
             $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->text('note')->nullable();
            $table->string('period'); // weekly, monthly, quarterly, yearly
            $table->date('scheduled_date');

            // Relasi
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // Admin GA
            $table->foreignId('worker_id')->constrained('users')->cascadeOnDelete();  // Tukang

            // Alur status
            // pending       → baru dibuat, menunggu konfirmasi tukang
            // confirmed     → tukang sudah konfirmasi
            // in_progress   → sedang dikerjakan (opsional, bisa skip ke done)
            // done          → tukang sudah lapor selesai
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'done'])
                  ->default('pending');

            // Timestamp alur
            $table->timestamp('worker_confirmed_at')->nullable(); // kapan tukang konfirmasi
            $table->timestamp('completed_at')->nullable();        // kapan tukang tandai selesai

            // Laporan penyelesaian dari tukang
            $table->text('completion_note')->nullable();
            $table->string('completion_photo')->nullable(); // path file di storage

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_maintenances');
    }
};