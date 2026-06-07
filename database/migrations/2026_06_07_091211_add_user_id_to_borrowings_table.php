<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('borrowings', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->nullable()->after('id');
        $table->unsignedBigInteger('asset_id')->nullable()->after('user_id');
        $table->unsignedBigInteger('destination_branch_id')->nullable()->after('asset_id');
        $table->unsignedBigInteger('destination_room_id')->nullable()->after('destination_branch_id');
        $table->unsignedBigInteger('request_branch_id')->nullable()->after('destination_room_id');
        $table->integer('qty')->default(1)->after('request_branch_id');
        $table->date('start_date')->nullable()->after('qty');
        $table->date('end_date')->nullable()->after('start_date');
        $table->string('reason')->nullable()->after('end_date');
        $table->string('notes')->nullable()->after('reason');
        $table->string('status')->default('requested')->after('notes');
    });
}

public function down()
{
    Schema::table('borrowings', function (Blueprint $table) {
        $table->dropColumn([
            'user_id', 'asset_id', 'destination_branch_id',
            'destination_room_id', 'request_branch_id', 'qty',
            'start_date', 'end_date', 'reason', 'notes', 'status'
        ]);
    });
}
};
