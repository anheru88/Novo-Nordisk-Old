<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNvnClientsSapCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_clients_sap_codes', function (Blueprint $table) {
            $table->bigIncrements('id_sap_ode');
            $table->unsignedBigInteger('id_client');
            $table->string('sap_code')->unique();

            $table->foreign('id_client')->references('id_client')->on('nvn_clients')->onDelete('cascade'); // References x ID table clients 
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
        Schema::dropIfExists('nvn_clients_sap_codes');
    }
}
