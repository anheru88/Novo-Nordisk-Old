<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnProductxprices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_productxprices', function (Blueprint $table) {
            $table->bigIncrements('id_productxprices');
            $table->unsignedBigInteger('id_product');
            $table->unsignedBigInteger('id_pricelists');
            $table->string('prod_sap_code');
            $table->decimal('v_institutional_price',15,0)->default(0);
            $table->decimal('v_commercial_price',15,0)->default(0);
            $table->text('prod_increment_max')->nullable();
            $table->text('version');
            $table->text('active');
            $table->dateTime('prod_valid_date_ini');
            $table->dateTime('prod_valid_date_end');
            $table->timestamps();

            $table->foreign('id_product')->references('id_product')->on('nvn_products')->onDelete('cascade'); // References x ID table products 
            $table->foreign('id_pricelists')->references('id_pricelists')->on('nvn_priceslists')->onDelete('cascade'); // References x version field table nvn_priceslists

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_productxprices');
    }
}
