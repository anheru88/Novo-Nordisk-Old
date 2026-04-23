<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnProductxclientxscalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_productxclientxscales', function (Blueprint $table) {
            $table->bigIncrements('id_productxclient');
            $table->unsignedBigInteger('id_client');
            $table->unsignedBigInteger('id_product');
            $table->unsignedBigInteger('id_scale');
            $table->timestamps();

            $table->foreign('id_scale')->references('id_scale')->on('nvn_product_scales')->onDelete('cascade'); // References x ID table nvn_product_scales  
            $table->foreign('id_client')->references('id_client')->on('nvn_clients')->onDelete('cascade'); // References x ID table nvn_clients  
            $table->foreign('id_product')->references('id_product')->on('nvn_products')->onDelete('cascade'); // References x ID table nvn_products  

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_productxclientxscales');
    }
}
