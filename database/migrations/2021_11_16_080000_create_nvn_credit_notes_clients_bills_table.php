<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnCreditNotesClientsBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_credit_notes_clients_bills', function (Blueprint $table) {
            $table->bigIncrements('id_credit_notes_clients_b');
            $table->text('client_sap_code');
            $table->text('concept')->nullable();
            $table->text('bill');
            $table->unsignedBigInteger('id_credit_notes');
            $table->timestamps();

            /*Llaves Foraneas*/
            $table->foreign('id_credit_notes')->references('id_credit_notes')->on('nvn_credit_notes')->onDelete('cascade'); // References x ID table nvn_notas
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_credit_notes_clients_bills');
    }
}
