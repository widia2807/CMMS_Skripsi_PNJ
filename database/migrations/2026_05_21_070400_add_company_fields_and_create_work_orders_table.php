<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Tambah kolom ke tabel companies ──────────────────────────
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'logo')) {
                $table->string('logo')->nullable()->after('name');
            }
            if (!Schema::hasColumn('companies', 'address')) {
                $table->string('address')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('companies', 'phone')) {
                $table->string('phone')->nullable()->after('address');
            }
            if (!Schema::hasColumn('companies', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('companies', 'manager_signature')) {
                // TTD Manager GA — disimpan sebagai path file
                $table->string('manager_signature')->nullable()->after('email');
            }
            if (!Schema::hasColumn('companies', 'manager_name')) {
                $table->string('manager_name')->nullable()->after('manager_signature');
            }
        });

        // ── 2. Buat tabel work_orders ────────────────────────────────────
        if (!Schema::hasTable('work_orders')) {
            Schema::create('work_orders', function (Blueprint $table) {
                $table->id();
                $table->string('wo_number')->unique();           // SPK-REP-2026-0001
                $table->enum('type', ['repair', 'scheduled']);   // jenis WO

                // Relasi ke sumber data
                $table->foreignId('repair_request_id')
                      ->nullable()
                      ->constrained('repair_requests')
                      ->onDelete('cascade');
                $table->foreignId('scheduled_maintenance_id')
                      ->nullable()
                      ->constrained('scheduled_maintenances')
                      ->onDelete('cascade');

                // Data snapshot (agar WO tidak berubah walaupun request diubah)
                $table->string('title');
                $table->text('description')->nullable();
                $table->text('note')->nullable();               // instruksi tambahan dari admin
                $table->string('status')->default('issued');    // issued | confirmed | done

                // Relasi
                $table->foreignId('company_id')->constrained('companies');
                $table->foreignId('category_id')->nullable()->constrained('categories');
                $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories');
                $table->foreignId('branch_id')->nullable()->constrained('branches');
                $table->foreignId('worker_id')->nullable()      // teknisi / tukang
                      ->references('id')->on('users');
                $table->foreignId('created_by')                 // admin GA yang kirim
                      ->references('id')->on('users');

                // Jadwal
                $table->date('schedule_date')->nullable();
                $table->string('urgency')->nullable();           // low | medium | high
                $table->string('period')->nullable();            // weekly | monthly | quarterly | yearly (scheduled)

                // Penyelesaian
                $table->timestamp('worker_confirmed_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->text('completion_note')->nullable();
                $table->string('completion_photo')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'logo', 'address', 'phone', 'email',
                'manager_signature', 'manager_name',
            ]);
        });
    }
};