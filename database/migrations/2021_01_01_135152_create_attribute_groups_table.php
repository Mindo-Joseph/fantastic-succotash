<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('position')->default(1);
            $table->boolean('is_user_defined')->default(1);
            $table->bigInteger('attribute_family_id')->unsigned()->nullable();
            $table->unique(['attribute_family_id', 'name']);
            $table->foreign('attribute_family_id')->references('id')->on('attribute_families')->onDelete('cascade');
        });

        Schema::create('attribute_group_mappings', function (Blueprint $table) {
            $table->bigInteger('attribute_id')->unsigned()->nullable();
            $table->bigInteger('attribute_group_id')->unsigned()->nullable();
            $table->integer('position')->nullable();
            $table->primary(['attribute_id', 'attribute_group_id']);
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->foreign('attribute_group_id')->references('id')->on('attribute_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_group_mappings');

        Schema::dropIfExists('attribute_groups');
    }
}
