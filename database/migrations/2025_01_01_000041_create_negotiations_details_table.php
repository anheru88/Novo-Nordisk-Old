<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('negotiations_details', function (Blueprint $table) {
            $table->increments('id_negotiation_det');
            $table->unsignedInteger('id_negotiation');
            $table->unsignedInteger('id_client');
            $table->unsignedInteger('id_product');
            $table->integer('id_concept')->nullable();
            $table->text('aclaracion')->nullable();
            $table->text('suj_volumen')->nullable();
            $table->text('quantity')->nullable();
            $table->integer('units')->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->text('discount_type')->nullable();
            $table->text('discount_acum')->nullable();
            $table->text('observations')->nullable();
            $table->unsignedInteger('id_prod_auth_level')->nullable();
            $table->text('authlevel')->nullable();
            $table->integer('is_valid');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->integer('id_quotation')->nullable();
            $table->integer('id_scale')->nullable();
            $table->text('id_scale_lvl')->nullable();
            $table->text('visible')->nullable();
            $table->integer('warning')->default(0);
            $table->foreign(['id_client'], 'nvn_negotiations_details_id_client_foreign')->references(['id_client'])->on('clients')->cascadeOnDelete();
            $table->foreign(['id_negotiation'], 'nvn_negotiations_details_id_negotiation_foreign')->references(['id_negotiation'])->on('negotiations')->cascadeOnDelete();
            $table->foreign(['id_prod_auth_level'], 'nvn_negotiations_details_id_prod_auth_level_foreign')->references(['id_level'])->on('product_auth_levels');
            $table->foreign(['id_product'], 'nvn_negotiations_details_id_product_foreign')->references(['id_product'])->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negotiations_details');
    }
};
