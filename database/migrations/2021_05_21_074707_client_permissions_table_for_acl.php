<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClientPermissionsTableForAcl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_permissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->bigInteger('permission_id');					
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_permissions', function (Blueprint $table) {
            Schema::drop('client_permissions');
        });
    }
}
