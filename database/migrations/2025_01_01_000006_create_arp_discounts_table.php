<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('arp_discounts', function (Blueprint $table) {
            $table->increments('id_discount');
            $table->string('discount_name', 191);
            $table->decimal('discount_percentage', 10, 2);
            $table->integer('discount_units')->nullable();
            $table->json('discount_clients');
            $table->json('discount_products');
            $table->json('discount_months');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arp_discounts');
    }
};
