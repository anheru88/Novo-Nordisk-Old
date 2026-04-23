<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnNegotiationxcommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_negotiationxcomments', function (Blueprint $table) {
            $table->bigIncrements('id_negotiationxcomments');
            $table->unsignedBigInteger('id_negotiation')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->text('type_comment')->nullable();
            $table->text('text_comment')->nullable();
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
        Schema::dropIfExists('nvn_negotiationxcomments');
    }
}
