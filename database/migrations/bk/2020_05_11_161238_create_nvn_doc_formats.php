<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnDocFormats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_doc_formats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_formattype');
            $table->text('title')->nullable();
            $table->text('body')->nullable();
            $table->text('conditions_time')->nullable();
            $table->text('conditions_content')->nullable();
            $table->text('conditions_special')->nullable();
            $table->text('terms_title')->nullable();
            $table->text('terms_content')->nullable();
            $table->text('sign_name')->nullable();
            $table->text('sign_charge')->nullable();
            $table->text('sign_image')->nullable();
            $table->text('footer')->nullable();
            $table->text('active');
            $table->timestamps();

            $table->foreign('id_formattype')->references('id_formattype')->on('nvn_doc_format_types'); // References x ID table users
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_doc_formats');
    }
}
