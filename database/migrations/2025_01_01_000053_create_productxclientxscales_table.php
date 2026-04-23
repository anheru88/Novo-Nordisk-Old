<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('productxclientxscales', function (Blueprint $table) {
            $table->bigIncrements('id_productxclient');
            $table->unsignedInteger('id_client');
            $table->unsignedInteger('id_product');
            $table->unsignedInteger('id_scale');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['id_client'], 'nvn_productxclientxscales_id_client_foreign')->references(['id_client'])->on('clients')->cascadeOnDelete();
            $table->foreign(['id_product'], 'nvn_productxclientxscales_id_product_foreign')->references(['id_product'])->on('products')->cascadeOnDelete();
            $table->foreign(['id_scale'], 'nvn_productxclientxscales_id_scale_foreign')->references(['id_scale'])->on('product_scales')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productxclientxscales');
    }
};
