<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount',12,2)->unsigned()->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->tinyInteger('type')->default(0)->comment('0- Fixed, 1- percentage, 2-fixed per product')->nullable();
            $table->tinyInteger('allow_free_delivery')->default(0)->comment('0- No, 1- yes')->nullable();
            $table->integer('minimum_spend')->unsigned()->nullable();
            $table->integer('maximum_spend')->unsigned()->nullable();
            $table->tinyInteger('first_order_only')->default(0)->comment('0- No, 1- yes')->nullable();
            $table->tinyInteger('limit_per_user')->nullable();
            $table->tinyInteger('limit_total')->nullable();
            $table->tinyInteger('Paid_by_vendor_admin')->nullable();
            $table->tinyInteger('is_deleted')->default(0)->comment('0- No, 1- yes')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('promocodes');
    }
}
