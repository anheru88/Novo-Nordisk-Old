<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quotations_details', function (Blueprint $table) {
            $table->increments('id_quota_det');
            $table->unsignedInteger('id_quotation');
            $table->unsignedInteger('id_client');
            $table->unsignedInteger('id_product');
            $table->unsignedInteger('id_payterm');
            $table->integer('quantity');
            $table->integer('prod_cost');
            $table->text('time_discount')->nullable();
            $table->text('pay_discount');
            $table->text('price_uminima')->nullable();
            $table->integer('price_discount')->nullable();
            $table->unsignedInteger('id_prod_auth_level')->nullable();
            $table->string('authlevel')->nullable();
            $table->integer('is_valid')->default(1);
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['id_client'], 'nvn_quotations_details_id_client_foreign')->references(['id_client'])->on('clients')->cascadeOnDelete();
            $table->foreign(['id_payterm'], 'nvn_quotations_details_id_payterm_foreign')->references(['id_payterms'])->on('payment_terms');
            $table->foreign(['id_prod_auth_level'], 'nvn_quotations_details_id_prod_auth_level_foreign')->references(['id_level'])->on('product_auth_levels');
            $table->foreign(['id_product'], 'nvn_quotations_details_id_product_foreign')->references(['id_product'])->on('products');
            $table->foreign(['id_quotation'], 'nvn_quotations_details_id_quotation_foreign')->references(['id_quotation'])->on('quotations')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations_details');
    }
};
