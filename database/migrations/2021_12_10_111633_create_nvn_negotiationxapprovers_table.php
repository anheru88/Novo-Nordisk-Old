<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnNegotiationxapproversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_negotiationxapprovers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('answer')->nullable();
            $table->unsignedBigInteger('negotiation_id');
            $table->unsignedBigInteger('user_id');
            //Foreign keys relationships
            $table->foreign('negotiation_id')->references('id_negotiation')->on('nvn_negotiations')->constrained()->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->constrained();
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
        Schema::dropIfExists('nvn_negotiationxapprovers');
    }
}
