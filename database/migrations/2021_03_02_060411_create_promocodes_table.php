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
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('uses')->unsigned()->nullable();
            $table->integer('max_uses')->unsigned()->nullable();
            $table->smallInteger('max_uses_user')->unsigned()->nullable();
            $table->tinyInteger('type')->unsigned()->default(1)->comment('1 - voucher, 2 - discount, 3 - sale');
            $table->integer('discount_amount')->unsigned()->nullable();
            $table->integer('discount_percentage')->unsigned()->nullable();
            $table->smallInteger('position')->unsigned()->default(1)->comment('similar like priority');
            $table->boolean('is_fixed')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->index('uses');
            $table->index('max_uses');
            $table->index('is_fixed');
            $table->index('type');
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