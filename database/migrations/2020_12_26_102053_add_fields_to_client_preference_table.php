<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToClientPreferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->tinyInteger('fb_login')->default(0)->comment('1 - enable, 0 - disable')->index();
            $table->tinyInteger('twitter_login')->default(0)->comment('1 - enable, 0 - disable')->index();
            $table->tinyInteger('google_login')->default(0)->comment('1 - enable, 0 - disable')->index();
            $table->tinyInteger('apple_login')->default(0)->comment('1 - enable, 0 - disable')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_preferences', function($table) {
            $table->dropColumn('fb_login');
            $table->dropColumn('twitter_login');
            $table->dropColumn('google_login');
            $table->dropColumn('apple_login');
        });
    }
}