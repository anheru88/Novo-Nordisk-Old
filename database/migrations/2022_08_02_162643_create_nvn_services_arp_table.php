<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnServicesArpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_services_arp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default('AB')->unique();
            $table->unsignedBigInteger('arps_id');
            $table->timestamps();

            $table->foreign('arps_id')->references('id')->on('nvn_arps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_services_arp');
    }
}
