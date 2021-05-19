<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorPaymentOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_payment_options', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->bigInteger('payment_option_id')->unsigned()->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 inactive, 1 active, 2 delete');
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('payment_option_id')->references('id')->on('payment_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_payment_options');
    }
}
