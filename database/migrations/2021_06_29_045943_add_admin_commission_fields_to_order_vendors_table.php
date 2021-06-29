<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminCommissionFieldsToOrderVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_vendors', function (Blueprint $table) {
            $table->decimal('admin_commission_percentage_amount', 10, 2)->nullable();
            $table->decimal('admin_commission_fixed_amount', 10, 2)->nullable();
            $table->tinyInteger('coupon_paid_by')->nullable()->comment('0-Vendor, 1-Admin')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_vendors', function (Blueprint $table) {
            $table->dropColumn('admin_commission_percentage_amount');
            $table->dropColumn('admin_commission_fixed_amount');
            $table->dropColumn('coupon_paid_by');
        });
    }
}
