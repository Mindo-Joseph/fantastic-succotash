<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropRefferedByFromUserRefferalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_refferals', function (Blueprint $table) {
            //
            Schema::dropIfExists('refferal_code');
            Schema::dropIfExists('reffered_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_refferals', function (Blueprint $table) {
            $table->string('refferal_code')->nullable();
            $table->string('reffered_by')->nullable();
        });
    }
}
