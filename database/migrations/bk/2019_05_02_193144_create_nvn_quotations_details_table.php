<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNvnQuotationsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_quotations_details', function (Blueprint $table) {
            $table->bigIncrements('id_quota_det');
            $table->unsignedBigInteger('id_quotation');
            $table->unsignedBigInteger('id_client');
            $table->unsignedBigInteger('id_product');
            $table->unsignedBigInteger('id_payterm');
            $table->integer('quantity');
            $table->integer('prod_cost');
            $table->integer('time_discount');
            $table->text('pay_discount');
            $table->integer('commercial_discount')->nullable();
            $table->integer('price_discount')->nullable();
            $table->bigInteger('totalValue');
            $table->unsignedBigInteger('id_prod_auth_level')->nullable();
            $table->text('authlevel')->nullable();
            $table->integer('is_valid');

            /*Llaves Foraneas*/      
            $table->foreign('id_quotation')->references('id_quotation')->on('nvn_quotations')->onDelete('cascade'); // References x ID table quotation      
            $table->foreign('id_client')->references('id_client')->on('nvn_clients')->onDelete('cascade'); // References x ID table clients 
            $table->foreign('id_product')->references('id_product')->on('nvn_products'); // References x ID table products 
            $table->foreign('id_payterm')->references('id_payterms')->on('nvn_payment_terms'); // References x ID table payterms
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
        Schema::dropIfExists('nvn_quotations_details');
    }
}
