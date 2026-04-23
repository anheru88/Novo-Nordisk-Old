<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnPriceslists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_priceslists', function (Blueprint $table) {
            $table->bigIncrements('id_pricelists');
            $table->unsignedBigInteger('id_authorizer_user');
            $table->text('list_version');
            $table->text('active');
            $table->timestamps(); 

            $table->foreign('id_authorizer_user')->references('id')->on('users'); // References x ID table users
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_priceslists');
    }
}
