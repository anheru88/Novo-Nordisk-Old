<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnNegotiationsErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_negotiations_errors', function (Blueprint $table) {
            $table->bigIncrements('id_negotiations_errors');
            $table->unsignedBigInteger('id_negotiation_det');
            $table->text('negotiation_error');
            $table->timestamps();

            /*Llaves Foraneas*/
            $table->foreign('id_negotiation_det')->references('id_negotiation_det')->on('nvn_negotiations_details')->onDelete('cascade'); // References x ID table nvn_notas
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_negotiations_errors');
    }
}
