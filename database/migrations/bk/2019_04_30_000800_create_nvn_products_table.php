<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNvnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_products', function (Blueprint $table) {
            $table->bigIncrements('id_product');
            $table->string('prod_name')->unique();
            $table->string('prod_sap_code')->unique();
            $table->text('prod_commercial_name');
            $table->text('prod_generic_name');
            $table->string('prod_invima_reg')->unique();
            $table->string('prod_cum')->unique();
            $table->unsignedBigInteger('id_prod_line')->index();
            $table->text('prod_package');
            $table->text('prod_package_unit');
            $table->unsignedBigInteger('id_measure_unit')->index();
            $table->text('is_prod_regulated');
            $table->unsignedBigInteger('prod_obesidad')->index()->nullable();
            $table->unsignedBigInteger('prod_insumo')->index()->nullable();
            $table->decimal('v_institutional_price',15,0)->default(0);
            $table->decimal('v_commercial_price',15,0)->default(0);
            $table->dateTime('prod_valid_date_ini');
            $table->dateTime('prod_valid_date_end');
            $table->text('prod_increment_max')->nullable();
            $table->text('renovacion')->nullable();
            $table->text('comments')->nullable();
            $table->text('extension_time')->nullable();
            $table->unsignedBigInteger('created_by')->index();

            /*Llaves Foraneas*/            
            $table->foreign('id_prod_line')->references('id_line')->on('nvn_product_lines')->onDelete('cascade'); // References x ID table nvn_products
            $table->foreign('id_measure_unit')->references('id_unit')->on('nvn_product_measure_units'); // References x ID table nvn_products
            $table->foreign('created_by')->references('id')->on('users'); // References x ID table users
            $table->timestamps();
        });

        Schema::create('nvn_products_h', function (Blueprint $table) {
            $table->bigIncrements('id_product');
            $table->unsignedBigInteger('id_product_h')->index();
            $table->integer('modification_type')->index();
            $table->decimal('v_institutional_price',15,0)->default(0);
            $table->decimal('v_commercial_price',15,0)->default(0);
            $table->dateTime('prod_valid_date_ini');
            $table->dateTime('prod_valid_date_end');
            $table->text('prod_increment_max')->nullable();
            $table->text('renovacion')->nullable();
            $table->text('comments')->nullable();
            $table->text('extension_time')->nullable();
            

            /*Llaves Foraneas*/            
            $table->foreign('id_product_h')->references('id_product')->on('nvn_products')->onDelete('cascade'); // References x ID table nvn_products
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
        Schema::drop('nvn_products');
        Schema::drop('nvn_products_h');
    }
}
