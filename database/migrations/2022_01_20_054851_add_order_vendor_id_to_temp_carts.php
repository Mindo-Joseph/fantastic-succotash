<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderVendorIdToTempCarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_carts', function (Blueprint $table) {
            $table->unsignedBigInteger('order_vendor_id')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_carts', function (Blueprint $table) {
            $table->dropColumn('order_vendor_id');
            $table->dropColumn('address_id');
        });
    }
}
