<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNvnDocRepositoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_doc_repository', function (Blueprint $table) {
            $table->bigIncrements('id_doc');
            $table->unsignedBigInteger('id_folder')->nullable();
            $table->string('doc_name')->nullable();
            $table->timestamps();
            $table->foreign('id_folder')->references('id_folder')->on('nvn_folder_repository')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_doc_repository');
    }
}
