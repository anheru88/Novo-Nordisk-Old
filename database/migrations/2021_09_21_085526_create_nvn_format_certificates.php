<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnFormatCertificates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_format_certificates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_formattype');
            $table->string('country');
            $table->string('reference');
            $table->string('header_body');
            $table->text('body');
            $table->string('footer_body');
            $table->string('user_firm');
            $table->string('user_name');
            $table->string('user_position');
            $table->string('page_name');
            $table->string('footer_column1_1');
            $table->string('footer_column1_2');
            $table->string('footer_column1_3');
            $table->string('footer_column2_1');
            $table->string('footer_column3_1');
            $table->string('active');
            $table->timestamps();
            // foreign
            $table->foreign('id_formattype')->references('id_formattype')->on('nvn_doc_format_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_format_certificates');
    }
}
