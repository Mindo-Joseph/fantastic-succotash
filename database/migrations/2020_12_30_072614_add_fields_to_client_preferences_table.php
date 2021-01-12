<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToClientPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->tinyInteger('is_hyperlocal')->default(0)->comment('0 - no, 1 - yes')->index();
            $table->tinyInteger('need_delivery_service')->default(0)->comment('0 - no, 1 - yes')->index();
            $table->string('dispatcher_key_1', 100)->nullable();
            $table->string('dispatcher_key_2', 100)->nullable();
        });

        Schema::table('client_preferences', function (Blueprint $table) {
            $table->foreign('web_template_id')->references('id')->on('templates')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('app_template_id')->references('id')->on('templates')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->dropForeign('client_preferences_app_template_id_foreign');
            $table->dropForeign('client_preferences_web_template_id_foreign');
        });
    }
}
