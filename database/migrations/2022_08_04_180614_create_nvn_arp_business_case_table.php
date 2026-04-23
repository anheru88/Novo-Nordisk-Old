<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnArpBusinessCaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_arp_business_case', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('arp_id');
            $table->unsignedBigInteger('brand_id');
            $table->decimal('pbc',30,2)->nullable();
            $table->timestamps();

            $table->foreign('arp_id')->references('id')->on('nvn_arps')->onDelete('cascade');
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
        Schema::dropIfExists('nvn_arp_business_case');
    }
}
