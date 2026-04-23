<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnNegotiationxdocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_negotiationxdocs', function (Blueprint $table) {
            $table->bigIncrements('id_negotiationxdocs');
            $table->unsignedBigInteger('id_negotiation')->nullable();
            $table->string('doc_name')->nullable();
            $table->timestamps();
            $table->foreign('id_negotiation')->references('id_negotiation')->on('nvn_negotiations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_negotiationxdocs');
    }
}
