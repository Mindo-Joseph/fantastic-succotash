<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialLoginField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('facebook_auth_id')->nullable();
            $table->string('twitter_auth_id')->nullable();
            $table->string('google_auth_id')->nullable();
            $table->string('apple_auth_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->string('facebook_auth_id')->nullable();
            $table->string('twitter_auth_id')->nullable();
            $table->string('google_auth_id')->nullable();
            $table->string('apple_auth_id')->nullable();
        });
    }
}