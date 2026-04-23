<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id_product');
            $table->string('prod_name', 191);
            $table->string('prod_sap_code', 191);
            $table->text('prod_commercial_name');
            $table->text('prod_generic_name');
            $table->string('prod_invima_reg', 191);
            $table->string('prod_cum', 191)->nullable();
            $table->unsignedInteger('id_prod_line')->nullable();
            $table->text('prod_package');
            $table->text('prod_package_unit');
            $table->unsignedInteger('id_measure_unit')->nullable();
            $table->string('is_prod_regulated')->nullable()->default(0);
            $table->integer('prod_obesidad')->nullable();
            $table->integer('prod_insumo')->nullable();
            $table->decimal('v_institutional_price', 15, 0)->nullable()->default(0);
            $table->decimal('v_commercial_price', 15, 0)->nullable()->default(0);
            $table->timestamp('prod_valid_date_ini', 0)->nullable();
            $table->timestamp('prod_valid_date_end', 0)->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->text('prod_increment_max')->nullable()->default('N/A');
            $table->text('renovacion')->nullable()->default(0);
            $table->text('comments')->nullable();
            $table->text('extension_time')->nullable();
            $table->text('material')->nullable();
            $table->text('aditional_use')->nullable();
            $table->text('status')->nullable();
            $table->text('prod_concentration')->nullable();
            $table->bigInteger('id_brand')->nullable();
            $table->text('prod_commercial_unit')->nullable();
            $table->boolean('arp_divide')->nullable()->default(false);
            $table->unique(['prod_sap_code'], 'nvn_products_prod_sap_code_unique');
            $table->foreign(['created_by'], 'nvn_products_created_by_foreign')->references(['id'])->on('users');
            $table->foreign(['id_measure_unit'], 'nvn_products_id_measure_unit_foreign')->references(['id_unit'])->on('product_measure_units');
            $table->foreign(['id_prod_line'], 'nvn_products_id_prod_line_foreign')->references(['id_line'])->on('product_lines')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
