<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrdersTableOrderNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('item_count');
            $table->dropColumn('tax_rate_id');
            $table->dropColumn('vendor_count');
            $table->dropColumn('promocode_id');
            $table->dropColumn('payment_status');
            $table->dropColumn('recipient_name');
            $table->dropColumn('recipient_email');
            $table->dropColumn('recipient_number');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->string('created_by')->after('id')->nullable();
            $table->string('order_number')->after('created_by')->nullable();
            $table->tinyInteger('payment_option_id')->after('order_number')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_number')->nullable();
        });
    }
}
