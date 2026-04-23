<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnNegotiationConcepts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_negotiation_concepts', function (Blueprint $table) {
            $table->bigIncrements('id_negotiation_concepts');
            $table->text('type_concept')->nullable();
            $table->text('concept_percentaje')->nullable();
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
        Schema::dropIfExists('nvn_negotiation_concepts');
    }
}
