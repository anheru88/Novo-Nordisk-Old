<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNvnProductScalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_product_scales', function (Blueprint $table) {
            $table->bigIncrements('id_scale');
            $table->unsignedBigInteger('id_product');
            $table->integer('scale_number');
            $table->integer('scale_level');
            $table->integer('scale_discount');
            $table->integer('scale_min');
            $table->integer('scale_max');
            $table->timestamps();

            $table->foreign('id_product')->references('id_product')->on('nvn_products')->onDelete('cascade'); // References x ID table quotation  

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_product_scales');
    }
}
