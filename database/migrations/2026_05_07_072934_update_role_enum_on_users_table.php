<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    DB::statement("ALTER TABLE users 
        MODIFY role ENUM('super_admin','admin','pic','technician','management') 
        NOT NULL DEFAULT 'pic'");
}

public function down()
{
    DB::statement("ALTER TABLE users 
        MODIFY role ENUM('super_admin','admin','pic','technician') 
        NOT NULL DEFAULT 'pic'");
}
};
