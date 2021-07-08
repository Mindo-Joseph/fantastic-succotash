<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionFeaturesListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_features_list', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('Description')->nullable();
            $table->enum('type',['User', 'Vendor'])->default('User');
            $table->tinyInteger('status')->unsigned()->default(1)->comment('0=Inactive, 1=Active');
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
        Schema::dropIfExists('subscription_features_list');
    }
}
