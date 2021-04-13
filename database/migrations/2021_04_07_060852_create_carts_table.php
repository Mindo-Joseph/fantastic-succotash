<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('browser_code', 100)->nullable();
            $table->tinyInteger('is_guest')->default(0)->comment('0- no, 1- yes');
            $table->tinyInteger('status')->default(0)->comment('0- pending, 1- Active, 2- blocked, 3- inactive');
            $table->tinyInteger('is_gift')->default(0)->comment('0- no, 1- yes');
            $table->smallInteger('items_count')->nullable();
            $table->smallInteger('items_qty')->nullable();
            $table->decimal('exchange_rate', 12, 4)->nullable();
            $table->bigInteger('base_currency_code')->unsigned()->nullable();
            $table->bigInteger('channel_currency_code')->unsigned()->nullable();
            $table->bigInteger('cart_currency_code')->unsigned()->nullable();
            $table->decimal('sub_total', 12, 2)->nullable();
            $table->decimal('base_sub_total', 12, 2)->nullable();
            $table->decimal('tax_total', 12, 2)->nullable();
            $table->decimal('base_tax_total', 12, 2)->nullable();
            $table->decimal('discount_amount', 12, 2)->nullable();
            $table->decimal('base_discount_amount', 12, 2)->nullable();
            $table->decimal('grand_total', 12, 2)->nullable();
            $table->decimal('base_grand_total', 12, 2)->nullable();
            $table->bigInteger('checkout_method')->unsigned()->nullable();
            $table->timestamp('conversion_time')->nullable();
            $table->dateTime('added_on')->nullable();
            $table->bigInteger('theme_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('categories')->onDelete('cascade');
            $table->index('is_guest');
            $table->index('is_gift');
            $table->index('status');
            $table->index('checkout_method');
            $table->index('exchange_rate');
            $table->index('added_on');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}