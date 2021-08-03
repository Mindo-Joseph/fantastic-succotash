<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorRegistrationDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_registration_documents', function (Blueprint $table) {
            $table->id();
            $table->string('file_type')->nullable();
            $table->timestamps();
        });
        Schema::create('vendor_registration_document_translations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('language_id')->unsigned();
            $table->bigInteger('vendor_registration_document_id')->unsigned();
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
        Schema::dropIfExists('vendor_registration_documents');
    }
}
