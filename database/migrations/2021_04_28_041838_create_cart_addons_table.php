<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_addons', function (Blueprint $table) {
            $table->unsignedBigInteger('cart_product_id');
            $table->unsignedBigInteger('addon_id');
            $table->unsignedBigInteger('option_id')->nullable();
            $table->timestamps();

            $table->foreign('cart_product_id')->references('id')->on('cart_products')->onDelete('cascade');
            $table->foreign('addon_id')->references('id')->on('addon_sets')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('addon_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_addons');
    }
}
