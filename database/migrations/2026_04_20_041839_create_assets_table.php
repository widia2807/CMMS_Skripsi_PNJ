<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('specification')->nullable();

            $table->foreignId('category_id')->nullable()->constrained();
            $table->foreignId('sub_category_id')->nullable()->constrained();

            $table->string('serial_number')->nullable();

            $table->enum('condition', ['baik', 'rusak ringan', 'rusak berat'])->default('baik');

            $table->string('location');
            $table->string('room')->nullable();

            $table->string('brand')->nullable();

            $table->foreignId('branch_id')->nullable()->constrained();

            $table->decimal('value', 15, 2)->nullable();
            $table->year('acquisition_year')->nullable();

            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('pic_id')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
