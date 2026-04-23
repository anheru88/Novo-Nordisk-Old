<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_scales', function (Blueprint $table) {
            $table->increments('id_scale');
            $table->unsignedInteger('id_product');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->text('scale_number')->nullable();
            $table->foreign(['id_product'], 'nvn_product_scales_id_product_foreign')->references(['id_product'])->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_scales');
    }
};
