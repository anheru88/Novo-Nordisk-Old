<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnQuotationxdocs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_quotationxdocs', function (Blueprint $table) {
            $table->bigIncrements('id_quotationxdoc');
            $table->unsignedBigInteger('id_quotation')->nullable();
            $table->string('doc_name')->nullable();
            $table->timestamps();
            $table->foreign('id_quotation')->references('id_quotation')->on('nvn_quotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_quotationxdocs');
    }
}
