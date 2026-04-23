<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNvnClientsFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_clients_files', function (Blueprint $table) {
            $table->bigIncrements('id_files');
            $table->unsignedBigInteger('id_client');
            $table->string('file_folder')->nullable();
            $table->string('file_name')->nullable();
            $table->timestamps();
            
            $table->foreign('id_client')->references('id_client')->on('nvn_clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_clients_files');
    }
}
