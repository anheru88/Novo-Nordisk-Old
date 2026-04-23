<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnArpSimulationsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_arp_simulations_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('arp_simulation_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('client_id');
            $table->text('cal_year_month')->nullable();
            $table->text('vol_type');
            $table->double('forecast_vol')->default(0);
            $table->double('sales_pack_vol')->default(0);
            $table->double('volume')->default(0);
            $table->double('asp_cop')->default(0);
            $table->double('amount_mcop')->default(0);
            $table->double('amount_dkk')->default(0);
            $table->text('currency')->nullable();
            $table->double('net_sales')->default(0);
            $table->text('version')->nullable();
            $table->text('versen')->nullable();
            $table->text('year');
            $table->text('quarter');
            $table->text('month');
            $table->unsignedBigInteger('cam_id');
            $table->text('cam_status')->nullable();
            $table->text('consumption_data')->nullable();
            $table->text('bu')->nullable();
            $table->text('group')->nullable();
            $table->text('cluster')->nullable();
            $table->text('region')->nullable();
            $table->timestamps();

            /*Llaves Foraneas*/
            $table->foreign('arp_simulation_id')->references('id')->on('nvn_arp_simulations')->onDelete('cascade');
            $table->foreign('brand_id')->references('id_brand')->on('nvn_brands')->onDelete('cascade');
            $table->foreign('product_id')->references('id_product')->on('nvn_products')->onDelete('cascade');
            $table->foreign('client_id')->references('id_client')->on('nvn_clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_arp_simulations_details');
    }
}
