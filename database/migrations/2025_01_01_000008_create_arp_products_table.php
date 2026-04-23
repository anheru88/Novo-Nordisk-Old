<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('arp_products', function (Blueprint $table) {
            $table->integer('id_product');
            $table->decimal('min_price', 10, 2)->nullable();
            $table->decimal('conversion', 10, 2)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->text('prod_sap_code')->nullable();
            $table->primary('id_product');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arp_products');
    }
};
