<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Perbaikan Gedung
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->string('spk_number')->nullable()->after('status');
            $table->timestamp('spk_sent_at')->nullable()->after('spk_number');
            $table->foreignId('spk_sent_by')->nullable()->after('spk_sent_at')
                  ->constrained('users')->onDelete('set null');
        });

        // Maintenance Terjadwal
        Schema::table('scheduled_maintenances', function (Blueprint $table) {
            $table->string('spk_number')->nullable()->after('status');
            $table->timestamp('spk_sent_at')->nullable()->after('spk_number');
            $table->foreignId('spk_sent_by')->nullable()->after('spk_sent_at')
                  ->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropForeign(['spk_sent_by']);
            $table->dropColumn(['spk_number', 'spk_sent_at', 'spk_sent_by']);
        });
        Schema::table('scheduled_maintenances', function (Blueprint $table) {
            $table->dropForeign(['spk_sent_by']);
            $table->dropColumn(['spk_number', 'spk_sent_at', 'spk_sent_by']);
        });
    }
};