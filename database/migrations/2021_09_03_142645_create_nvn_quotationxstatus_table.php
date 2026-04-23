<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnQuotationxstatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_quotationxstatus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('quotation_id');
            $table->timestamps();
            //keys foreigns relationships
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('quotation_id')->references('id_quotation')->on('nvn_quotations');
            $table->foreign('status_id')->references('status_id')->on('nvn_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_quotationxstatus');
    }
}
