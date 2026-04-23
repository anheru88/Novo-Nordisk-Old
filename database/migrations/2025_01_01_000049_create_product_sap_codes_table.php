<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_sap_codes', function (Blueprint $table) {
            $table->bigIncrements('id_product_sapcode');
            $table->unsignedInteger('id_product');
            $table->text('active')->default(1);
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->string('sap_code', 255);
            $table->unique(['sap_code'], 'nvn_product_sap_codes_sap_code_unique');
            $table->foreign(['id_product'], 'nvn_product_sapcodes_id_product_foreign')->references(['id_product'])->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_sap_codes');
    }
};
