<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Perbaikan Gedung
        if (Schema::hasTable('repair_requests')) {
            Schema::table('repair_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('repair_requests', 'spk_number')) {
                    $table->string('spk_number')->nullable()->after('status');
                }
                if (!Schema::hasColumn('repair_requests', 'spk_sent_at')) {
                    $table->timestamp('spk_sent_at')->nullable()->after('spk_number');
                }
                if (!Schema::hasColumn('repair_requests', 'spk_sent_by')) {
                    $table->foreignId('spk_sent_by')->nullable()->after('spk_sent_at')
                          ->constrained('users')->onDelete('set null');
                }
            });
        }

        // Maintenance Terjadwal
        if (Schema::hasTable('scheduled_maintenances')) {
            Schema::table('scheduled_maintenances', function (Blueprint $table) {
                if (!Schema::hasColumn('scheduled_maintenances', 'spk_number')) {
                    $table->string('spk_number')->nullable()->after('status');
                }
                if (!Schema::hasColumn('scheduled_maintenances', 'spk_sent_at')) {
                    $table->timestamp('spk_sent_at')->nullable()->after('spk_number');
                }
                if (!Schema::hasColumn('scheduled_maintenances', 'spk_sent_by')) {
                    $table->foreignId('spk_sent_by')->nullable()->after('spk_sent_at')
                          ->constrained('users')->onDelete('set null');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('repair_requests')) {
            Schema::table('repair_requests', function (Blueprint $table) {
                $table->dropForeign(['spk_sent_by']);
                $table->dropColumn(['spk_number', 'spk_sent_at', 'spk_sent_by']);
            });
        }

        if (Schema::hasTable('scheduled_maintenances')) {
            Schema::table('scheduled_maintenances', function (Blueprint $table) {
                $table->dropForeign(['spk_sent_by']);
                $table->dropColumn(['spk_number', 'spk_sent_at', 'spk_sent_by']);
            });
        }
    }
};