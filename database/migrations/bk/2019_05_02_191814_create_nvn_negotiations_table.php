<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNvnNegotiationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_negotiations', function (Blueprint $table) {
            $table->bigIncrements('id_negotiation');
            $table->unsignedBigInteger('id_client');
            $table->unsignedBigInteger('id_city');
            $table->integer('is_authorized')->default(0);
            $table->unsignedBigInteger('id_authorizer_user')->nullable();
            $table->unsignedBigInteger('id_channel');
            $table->integer('negotiation_value')->nullable();
            $table->text('id_auth_level')->nullable();
            $table->dateTime('negotiation_date_ini');
            $table->dateTime('negotiation_date_end');
            $table->integer('pre_approved')->default(0)->nullable();
            $table->text('comments')->nullable();
            $table->unsignedBigInteger('created_by');

            /*Llaves Foraneas*/           
            $table->foreign('id_client')->references('id_client')->on('nvn_clients')->onDelete('cascade'); // References x ID table clients 
            $table->foreign('id_city')->references('id_locations')->on('nvn_locations'); // References x ID table locations 
            $table->foreign('id_authorizer_user')->references('id')->on('users'); // References x ID table users
            $table->foreign('id_channel')->references('id_channel')->on('nvn_dist_channels'); // References x ID table dist channels
            $table->foreign('created_by')->references('id')->on('users'); // References x ID table users
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
        Schema::dropIfExists('nvn_negotiations');
    }
}
