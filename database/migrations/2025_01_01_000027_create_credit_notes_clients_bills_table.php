<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('credit_notes_clients_bills', function (Blueprint $table) {
            $table->bigIncrements('id_credit_notes_clients_b');
            $table->text('client_sap_code');
            $table->text('concept')->nullable();
            $table->text('bill');
            $table->unsignedBigInteger('id_credit_notes');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['id_credit_notes'], 'nvn_credit_notes_clients_bills_id_credit_notes_foreign')->references(['id_credit_notes'])->on('credit_notes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_notes_clients_bills');
    }
};
