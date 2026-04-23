<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('credit_notes_details_b', function (Blueprint $table) {
            $table->bigIncrements('id_credit_notes_details_b');
            $table->unsignedBigInteger('id_credit_notes_clients_b');
            $table->text('prod_sap_code');
            $table->integer('real_qty');
            $table->decimal('nc_value', 10, 2);
            $table->decimal('nc_individual', 10, 2);
            $table->text('tab_xls')->nullable();
            $table->text('concept')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['id_credit_notes_clients_b'], 'nvn_credit_notes_details_b_id_credit_notes_clients_b_foreign')->references(['id_credit_notes_clients_b'])->on('credit_notes_clients_bills')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_notes_details_b');
    }
};
