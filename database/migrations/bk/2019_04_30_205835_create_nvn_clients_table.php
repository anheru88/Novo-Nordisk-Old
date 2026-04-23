<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNvnClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_clients', function (Blueprint $table) {
            $table->bigIncrements('id_client');
            $table->unsignedBigInteger('id_client_type'); // Foreign clien type
            $table->text('client_name');
            $table->text('client_quote_name')->nullable();
            $table->string('client_nit')->unique();
            $table->string('client_sap_name')->unique()->nullable();
            $table->string('client_sap_code')->unique()->nullable();
            $table->unsignedBigInteger('id_client_channel'); // Foreign  channel
            $table->unsignedBigInteger('id_department');  // Foreign ubication
            $table->unsignedBigInteger('id_city');  // Foreign ubication
            $table->text('client_contact');
            $table->text('client_phone');
            $table->text('client_email');
            $table->decimal('client_credit',15,0)->nullable();
            $table->unsignedBigInteger('id_diab_contact'); // Foreign user
            $table->unsignedBigInteger('id_biof_contact')->nullable(); // Foreign user
            $table->text('client_address');
            $table->text('client_position');
            $table->text('active');
            $table->unsignedBigInteger('id_payterm');
            $table->unsignedBigInteger('created_by');

            /*Llaves Foraneas*/            
            $table->foreign('id_client_type')->references('id_type')->on('nvn_client_types')->onDelete('cascade'); // References x ID table client types
            $table->foreign('id_client_channel')->references('id_channel')->on('nvn_dist_channels'); // References x ID table client channel
            $table->foreign('id_department')->references('id_locations')->on('nvn_locations'); // References x ID table department
            $table->foreign('id_city')->references('id_locations')->on('nvn_locations'); // References x ID table department
            $table->foreign('id_diab_contact')->references('id')->on('users'); // References x ID table users
            $table->foreign('id_biof_contact')->references('id')->on('users'); // References x ID table users
            $table->foreign('created_by')->references('id')->on('users'); // References x ID table users
            $table->foreign('id_payterm')->references('id_payterms')->on('nvn_payment_terms'); // References x ID Payterms table

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
        Schema::drop('nvn_clients');
    }
}
