<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNvnProductAuthLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_product_auth_levels', function (Blueprint $table) {
            $table->bigIncrements('id_level');
            $table->unsignedBigInteger('id_product');
            $table->unsignedBigInteger('id_dist_channel');
            $table->unsignedBigInteger('id_level_discount');
            $table->unsignedBigInteger('id_pricelists');
            $table->text('discount_value')->nullable();
            $table->text('discount_price')->nullable();
            $table->text('version')->nullable();
            //$table->text('active')->nullable()->default(0);

            /*Foreign Keys*/            
            $table->foreign('id_product')->references('id_product')->on('nvn_products'); // References x ID table products
            $table->foreign('id_dist_channel')->references('id_channel')->on('nvn_dist_channels'); // References x ID table dist_channels
            $table->foreign('id_level_discount')->references('id_disc_level')->on('nvn_discount_levels')->onDelete('cascade'); // References x ID table discount levels
            //$table->foreign('id_pricelists')->references('id_pricelists')->on('nvn_priceslists')->onDelete('cascade'); // References x ID table discount levels

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
        Schema::dropIfExists('nvn_product_auth_levels');
    }
}
