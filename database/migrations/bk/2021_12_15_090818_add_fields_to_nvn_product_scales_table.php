<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToNvnProductScalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nvn_product_scales', function (Blueprint $table) {
            $table->unsignedBigInteger('id_channel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nvn_product_scales', function (Blueprint $table) {
            $table->dropColumn('id_channel');
        });
    }
}
