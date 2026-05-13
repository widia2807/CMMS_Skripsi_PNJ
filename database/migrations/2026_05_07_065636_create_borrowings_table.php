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
    Schema::create('borrowings', function (Blueprint $table) {
        $table->id();

        $table->foreignId('asset_id')->constrained()->cascadeOnDelete();

        $table->foreignId('request_branch_id')->constrained('branches');
        $table->foreignId('source_branch_id')->nullable()->constrained('branches');

        $table->enum('status', ['requested','approved','picked','returned','rejected'])
              ->default('requested');

        $table->date('start_date');
        $table->date('end_date');

        $table->text('notes')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
