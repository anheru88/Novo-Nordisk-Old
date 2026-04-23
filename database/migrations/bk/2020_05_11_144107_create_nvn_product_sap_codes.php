<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnProductSapcodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_product_sap_codes', function (Blueprint $table) {
            $table->bigIncrements('id_product_sapcode');
            $table->unsignedBigInteger('id_product');
            $table->string('sap_code')->unique();
            $table->string('active');
            $table->timestamps(); 

            $table->foreign('id_product')->references('id_product')->on('nvn_products'); // References x ID table users
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_product_sapcode');
    }
}
