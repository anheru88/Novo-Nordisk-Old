<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnNegotiationxstatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_negotiationxstatus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('negotiation_id');
            //keys foreigns relationships
            $table->foreign('status_id')->references('status_id')->on('nvn_status')->constrained()->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->constrained();
            $table->foreign('negotiation_id')->references('id_negotiation')->on('nvn_negotiations')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('nvn_negotiationxstatus');
    }
}
