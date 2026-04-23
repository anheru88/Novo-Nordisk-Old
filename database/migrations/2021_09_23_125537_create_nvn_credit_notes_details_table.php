<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnCreditNotesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_credit_notes_details', function (Blueprint $table) {
            $table->bigIncrements('id_credit_notes_details');
            $table->unsignedBigInteger('id_credit_notes_clients');
            $table->text('prod_sap_code');
            $table->integer('real_qty');
            $table->decimal('nc_value',20,2);
            $table->decimal('nc_individual',20,2);
            $table->text('tab_xls')->nullable();
            $table->text('concept')->nullable();
            $table->timestamps();

             /*Llaves Foraneas*/
             $table->foreign('id_credit_notes_clients')->references('id_credit_notes_clients')->on('nvn_credit_notes_clients')->onDelete('cascade'); // References x ID table nvn_notas
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_credit_notes_details');
    }
}
