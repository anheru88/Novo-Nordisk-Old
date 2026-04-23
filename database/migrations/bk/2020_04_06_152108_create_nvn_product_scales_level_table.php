<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnProductScalesLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_product_scales_level', function (Blueprint $table) {
            $table->bigIncrements('id_scale_level');
            $table->unsignedBigInteger('id_scale');
            $table->string('scale_discount');
            $table->string('scale_min');
            $table->string('scale_max');
            $table->timestamps();

            $table->foreign('id_scale')->references('id_scale')->on('nvn_product_scales')->onDelete('cascade'); // References x ID table quotation  

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_product_scales_level');
    }
}
