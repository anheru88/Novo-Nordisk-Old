<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('arp_sales', function (Blueprint $table) {
            $table->bigIncrements('id_sale');
            $table->text('bill_number')->nullable();
            $table->text('bill_quanty')->nullable();
            $table->decimal('bill_price', 10, 2)->nullable();
            $table->decimal('bill_net_value', 10, 2)->nullable();
            $table->decimal('bill_real_qty', 10, 2)->nullable();
            $table->timestamp('bill_date')->nullable();
            $table->text('client_sap_code')->nullable();
            $table->text('prod_sap_code')->nullable();
            $table->text('brand')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('id_sales_list')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arp_sales');
    }
};
