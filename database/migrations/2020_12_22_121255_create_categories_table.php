<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('icon')->nullable();
            $table->string('image', 150)->nullable();
            $table->string('url-slug', 150)->nullable();
            $table->tinyInteger('status')->default('1')->comment('0 - pending, 1 - active, 2 - blocked');
            $table->smallInteger('position')->default('1')->comment('for same position, display asc order');
            $table->tinyInteger('is_admin')->default('1')->comment('0 - no, 1 - yes');
            $table->tinyInteger('can_add_products')->default('1')->comment('0 - no, 1 - yes');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->bigInteger('merchant_id')->unsigned()->nullable();
            $table->string('display_mode')->nullable();
            $table->timestamps();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('merchant_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->index('name');
            $table->index('url-slug');
            $table->index('status');
            $table->index('is_admin');
            $table->index('position');
            $table->index('can_add_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}