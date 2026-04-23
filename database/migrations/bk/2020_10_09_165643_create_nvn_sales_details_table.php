<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnSalesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_sales_details', function (Blueprint $table) {
            $table->bigIncrements('id_sale_details');
            $table->unsignedBigInteger('id_sales');
            $table->text('client_sap_code');
            $table->text('prod_sap_code');
            $table->text('po_number');
            $table->text('payterm_code');
            $table->text('brand');
            $table->text('billT');
            $table->text('bill_doc');
            $table->text('bill_number');
            $table->text('bill_ltm');
            $table->text('bill_date');
            $table->integer('bill_quanty');
            $table->integer('bill_price');
            $table->integer('bill_net_value');
            $table->integer('bill_real_qty');
            $table->integer('unitxmaterial');
            $table->integer('volume');
            $table->double('value_mdkk');
            $table->timestamps();

             /*Llaves Foraneas*/
             $table->foreign('id_sales')->references('id_sales')->on('nvn_sales')->onDelete('cascade'); // References x ID table quotation
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_sales_details');
    }
}
