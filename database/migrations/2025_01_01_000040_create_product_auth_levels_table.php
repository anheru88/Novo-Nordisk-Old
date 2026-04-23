<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_auth_levels', function (Blueprint $table) {
            $table->increments('id_level');
            $table->unsignedInteger('id_product');
            $table->unsignedInteger('id_dist_channel');
            $table->unsignedInteger('id_level_discount');
            $table->text('discount_value');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->text('version')->nullable();
            $table->text('active')->nullable();
            $table->unsignedBigInteger('id_pricelists')->nullable();
            $table->text('discount_price')->nullable();
            $table->foreign(['id_dist_channel'], 'nvn_product_auth_levels_id_dist_channel_foreign')->references(['id_channel'])->on('dist_channels');
            $table->foreign(['id_level_discount'], 'nvn_product_auth_levels_id_level_discount_foreign')->references(['id_disc_level'])->on('discount_levels')->cascadeOnDelete();
            $table->foreign(['id_pricelists'], 'nvn_product_auth_levels_id_pricelists_fkey')->references(['id_pricelists'])->on('priceslists')->cascadeOnDelete();
            $table->foreign(['id_product'], 'nvn_product_auth_levels_id_product_foreign')->references(['id_product'])->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_auth_levels');
    }
};
