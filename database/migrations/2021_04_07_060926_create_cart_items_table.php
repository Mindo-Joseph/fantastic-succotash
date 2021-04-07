<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cart_id')->unsigned()->nullable();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->bigInteger('cart_rule_id')->unsigned()->nullable();
            $table->bigInteger('product_variant_id')->unsigned()->nullable();
            $table->bigInteger('tax_category_id')->unsigned()->nullable();
            $table->string('sku')->unsigned()->nullable();
            $table->string('type')->unsigned()->nullable();
            $table->integer('quantity')->unsigned()->default(1);
            $table->decimal('per_product_price', 12, 2)->nullable();
            $table->decimal('total_product_price', 12, 2)->nullable();
            $table->decimal('weight', 12, 3)->nullable();
            $table->decimal('tax_amount', 12, 2)->nullable();
            $table->decimal('base_tax_amount', 12, 2)->nullable();
            $table->integer('tax_percent')->unsigned()->default(1);
            $table->integer('discount_percent')->unsigned()->default(1);
            $table->decimal('discount_amount', 12, 2)->nullable();
            $table->decimal('base_discount_amount', 12, 2)->nullable();
            $table->decimal('additional', 12, 2)->nullable();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->decimal('payble_amount', 12, 2)->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}