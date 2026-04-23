<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales_details', function (Blueprint $table) {
            $table->bigIncrements('id_sale_details');
            $table->unsignedBigInteger('id_sales');
            $table->text('client_sap_code');
            $table->text('prod_sap_code');
            $table->text('po_number');
            $table->text('payterm_code');
            $table->text('brand');
            $table->text('bill_doc');
            $table->text('bill_number');
            $table->text('bill_ltm');
            $table->text('bill_date');
            $table->double('bill_quanty');
            $table->double('bill_price');
            $table->double('bill_net_value');
            $table->double('bill_real_qty');
            $table->double('unitxmaterial');
            $table->double('volume');
            $table->double('value_mdkk');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['id_sales'], 'nvn_sales_details_id_sales_foreign')->references(['id_sales'])->on('sales')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_details');
    }
};
