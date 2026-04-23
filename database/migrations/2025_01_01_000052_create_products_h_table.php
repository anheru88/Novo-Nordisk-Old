<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products_h', function (Blueprint $table) {
            $table->increments('id_product');
            $table->unsignedInteger('id_product_h');
            $table->text('modification_type');
            $table->text('comments');
            $table->decimal('v_institutional_price', 15, 0)->default(0);
            $table->decimal('v_commercial_price', 15, 0)->default(0);
            $table->timestamp('prod_valid_date_ini', 0);
            $table->timestamp('prod_valid_date_end', 0);
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->index(['modification_type'], 'nvn_products_h_modification_type_index');
            $table->foreign(['id_product_h'], 'nvn_products_h_id_product_h_foreign')->references(['id_product'])->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products_h');
    }
};
