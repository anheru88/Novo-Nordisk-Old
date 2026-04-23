<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNvnNegotiationsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_negotiations_details', function (Blueprint $table) {
            $table->bigIncrements('id_negotiation_det');
            $table->unsignedBigInteger('id_negotiation');
            $table->unsignedBigInteger('id_client');
            $table->unsignedBigInteger('id_product');
            $table->unsignedBigInteger('id_concept');
            $table->text('aclaracion');
            $table->integer('suj_volumen')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('units')->nullable();
            $table->integer('discount_type')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('discount_acum')->nullable();
            $table->text('observations')->nullable();
            $table->unsignedBigInteger('id_prod_auth_level')->nullable();
            $table->text('authlevel')->nullable();
            $table->integer('is_valid');

            /*Llaves Foraneas*/      
            $table->foreign('id_negotiation')->references('id_negotiation')->on('nvn_negotiations')->onDelete('cascade'); // References x ID table quotation      
            $table->foreign('id_client')->references('id_client')->on('nvn_clients')->onDelete('cascade'); // References x ID table clients 
            $table->foreign('id_product')->references('id_product')->on('nvn_products'); // References x ID table products 
            $table->foreign('id_concept')->references('id_concept')->on('nvn_concept_terms'); // References x ID table payterms
            //$table->foreign('id_prod_auth_level')->references('id_level')->on('nvn_product_auth_levels'); // References x ID table dist channels
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
        //
    }
}
