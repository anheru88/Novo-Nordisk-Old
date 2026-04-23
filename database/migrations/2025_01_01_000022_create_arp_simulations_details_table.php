<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('arp_simulations_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('arp_simulation_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('client_id');
            $table->text('cal_year_month')->nullable();
            $table->text('vol_type');
            $table->double('forecast_vol')->default(0);
            $table->double('sales_pack_vol')->default(0);
            $table->double('volume')->default(0);
            $table->double('asp_cop')->default(0);
            $table->double('amount_mcop')->default(0);
            $table->double('amount_dkk')->default(0);
            $table->text('currency')->nullable();
            $table->double('net_sales')->default(0);
            $table->text('version')->nullable();
            $table->text('versen')->nullable();
            $table->text('year');
            $table->text('quarter');
            $table->text('month');
            $table->bigInteger('cam_id');
            $table->text('cam_status')->nullable();
            $table->text('consumption_data')->nullable();
            $table->text('bu')->nullable();
            $table->text('cluster')->nullable();
            $table->text('region')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['arp_simulation_id'], 'nvn_arp_simulations_details_arp_simulation_id_foreign')->references(['id'])->on('arp_simulations')->cascadeOnDelete();
            $table->foreign(['brand_id'], 'nvn_arp_simulations_details_brand_id_foreign')->references(['id_brand'])->on('brands')->cascadeOnDelete();
            $table->foreign(['client_id'], 'nvn_arp_simulations_details_client_id_foreign')->references(['id_client'])->on('clients')->cascadeOnDelete();
            $table->foreign(['product_id'], 'nvn_arp_simulations_details_product_id_foreign')->references(['id_product'])->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arp_simulations_details');
    }
};
