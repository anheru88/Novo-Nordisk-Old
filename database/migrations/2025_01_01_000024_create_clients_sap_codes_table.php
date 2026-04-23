<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clients_sap_codes', function (Blueprint $table) {
            $table->increments('id_sap_code');
            $table->unsignedInteger('id_client')->nullable();
            $table->string('sap_code', 191)->nullable()->default(-1);
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->integer('client_sap_code')->nullable();
            $table->unique(['sap_code'], 'nvn_clients_sap_codes_sap_code_unique');
            $table->foreign(['id_client'], 'nvn_clients_sap_codes_id_client_foreign')->references(['id_client'])->on('clients')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients_sap_codes');
    }
};
