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
    Schema::create('users', function (Blueprint $table) {
        $table->id();

        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');

        // SYSTEM
        $table->enum('mode', ['company'])->default('company');
        $table->enum('system_type', ['full','lite'])->default('lite');

        $table->foreignId('company_id')->constrained()->cascadeOnDelete();
        $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();

        $table->enum('role', ['super_admin','admin','pic','technician'])->default('pic');

        // 🔥 STATUS USER
        $table->enum('status', ['pending','active','inactive'])->default('pending');

        // 🔥 WAJIB GANTI PASSWORD
        $table->boolean('must_change_password')->default(true);

        // 🔥 EMAIL VERIFICATION / AKTIVASI
        $table->timestamp('email_verified_at')->nullable();

        // 🔥 TOKEN RESET PASSWORD (optional tapi bagus)
        $table->string('reset_token')->nullable();

        $table->string('phone')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
