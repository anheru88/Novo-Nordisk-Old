<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnCreditNotesDetailsBTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_credit_notes_details_b', function (Blueprint $table) {
            $table->bigIncrements('id_credit_notes_details_b');
            $table->unsignedBigInteger('id_credit_notes_clients_b');
            $table->text('prod_sap_code');
            $table->integer('real_qty');
            $table->integer('nc_value');
            $table->integer('nc_individual');
            $table->text('tab_xls')->nullable();
            $table->text('concept')->nullable();
            $table->timestamps();

            /*Llaves Foraneas*/
            $table->foreign('id_credit_notes_clients_b')->references('id_credit_notes_clients_b')->on('nvn_credit_notes_clients_bills')->onDelete('cascade'); // References x ID table nvn_notas
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_credit_notes_details_b');
    }
}
