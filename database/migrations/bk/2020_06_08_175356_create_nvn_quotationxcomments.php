<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnQuotationxcomments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_quotationxcomments', function (Blueprint $table) {
            $table->bigIncrements('id_quotationxcomments');
            $table->unsignedBigInteger('id_quotation')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->text('type_comment')->nullable();
            $table->text('text_comment')->nullable();
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
        Schema::dropIfExists('nvn_quotationxcomments');
    }
}
