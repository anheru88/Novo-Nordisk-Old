<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('productxprices', function (Blueprint $table) {
            $table->bigIncrements('id_productxprices');
            $table->unsignedInteger('id_product');
            $table->unsignedBigInteger('id_pricelists');
            $table->string('prod_sap_code', 255);
            $table->decimal('v_institutional_price', 15, 0)->default(0);
            $table->decimal('v_commercial_price', 15, 0)->default(0);
            $table->text('prod_increment_max')->nullable();
            $table->text('version');
            $table->text('active')->nullable()->default(0);
            $table->timestamp('prod_valid_date_ini', 0);
            $table->timestamp('prod_valid_date_end', 0);
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->text('comments')->nullable();
            $table->foreign(['id_pricelists'], 'nvn_productxprices_id_pricelists_foreign')->references(['id_pricelists'])->on('priceslists')->cascadeOnDelete();
            $table->foreign(['id_product'], 'nvn_productxprices_id_product_foreign')->references(['id_product'])->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productxprices');
    }
};
