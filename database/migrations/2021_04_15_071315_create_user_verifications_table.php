<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_verifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('email_token', 20)->nullable();
            $table->timestamp('email_token_valid_till')->nullable();
            $table->string('phone_token', 20)->nullable();
            $table->timestamp('phone_token_valid_till')->nullable();
            $table->tinyInteger('is_email_verified')->default(0)->comment('1 for yes, 0 for no');
            $table->tinyInteger('is_phone_verified')->default(0)->comment('1 for yes, 0 for no');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_verifications');
    }
}
