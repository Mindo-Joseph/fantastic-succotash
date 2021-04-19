<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodeRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocode_restrictions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('promocode_id')->unsigned()->nullable();
            $table->tinyInteger('restriction_type')->default(0)->comment('0- product, 1-vendor, 2-category')->nullable();
            $table->bigInteger('included_product')->unsigned()->nullable();
            $table->bigInteger('excluded_product')->unsigned()->nullable();
            $table->unsignedBigInteger('excluded_vendor')->nullable();
            $table->unsignedBigInteger('included_vendor')->nullable();
            $table->unsignedBigInteger('included_category')->nullable();
            $table->unsignedBigInteger('excluded_category')->nullable();
            $table->timestamps();
            $table->foreign('promocode_id')->references('id')->on('promocodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promocode_restrictions');
    }
}
