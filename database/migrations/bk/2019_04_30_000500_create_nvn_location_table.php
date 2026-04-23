<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNvnLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_locations', function (Blueprint $table) {
            $table->bigIncrements('id_locations');
            $table->integer('loc_codedep');
            $table->text('loc_name');
            $table->integer('loc_codecity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('nvn_locations');
    }
}
