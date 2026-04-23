<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnArpServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_arp_service', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('services_arp_id');
            $table->unsignedBigInteger('brand_id');
            $table->decimal('volume',30,2)->nullable();
            $table->decimal('value_cop',30,2)->nullable();
            $table->timestamps();

            $table->foreign('services_arp_id')->references('id')->on('nvn_services_arp')->onDelete('cascade');
            $table->foreign('brand_id')->references('id_brand')->on('nvn_brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_arp_service');
    }
}
