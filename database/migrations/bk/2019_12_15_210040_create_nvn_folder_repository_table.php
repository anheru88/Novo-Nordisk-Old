<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNvnFolderRepositoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_folder_repository', function (Blueprint $table) {
            $table->bigIncrements('id_folder');
            $table->string('folder_name')->nullable();
            $table->string('folder_url')->nullable();
            $table->unsignedBigInteger('id_parent')->nullable();
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
        Schema::dropIfExists('nvn_folder_repository');
    }
}
