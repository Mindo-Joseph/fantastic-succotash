<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->tinyInteger('type')->default(1)->comment('1 - Silver, 2 - Gold, 3 - Platinum, 4 - Diamond');
            $table->decimal('balance', 10, 2)->default(0)->nullable();
            $table->string('card_id')->nullable();
            $table->string('card_qr_code')->nullable();
            $table->string('meta_field')->nullable();
            $table->unsignedBigInteger('currency_id')->default(147)->nullable();
            $table->timestamps();

            $table->index('card_id');
            $table->index('type');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}
