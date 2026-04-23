<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id_client');
            $table->unsignedInteger('id_client_type');
            $table->text('client_name');
            $table->text('client_quote_name')->nullable();
            $table->string('client_nit', 191)->nullable();
            $table->string('client_sap_name', 191)->nullable();
            $table->string('client_sap_code', 191)->nullable();
            $table->unsignedInteger('id_client_channel');
            $table->unsignedInteger('id_department')->nullable();
            $table->unsignedInteger('id_city')->nullable();
            $table->text('client_contact')->nullable();
            $table->text('client_phone')->nullable();
            $table->text('client_email')->nullable();
            $table->decimal('client_credit', 15, 0)->nullable();
            $table->unsignedBigInteger('id_diab_contact');
            $table->unsignedBigInteger('id_biof_contact')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->text('client_address')->nullable();
            $table->text('client_position')->nullable();
            $table->text('client_area_code')->nullable();
            $table->text('active')->nullable()->default(0);
            $table->unsignedInteger('id_payterm')->nullable();
            $table->text('client_email_secondary')->nullable();
            $table->unique(['client_sap_code'], 'nvn_clients_client_sap_code_unique');
            $table->unique(['client_sap_name'], 'nvn_clients_client_sap_name_unique');
            $table->foreign(['created_by'], 'nvn_clients_created_by_foreign')->references(['id'])->on('users');
            $table->foreign(['id_biof_contact'], 'nvn_clients_id_biof_contact_foreign')->references(['id'])->on('users');
            $table->foreign(['id_city'], 'nvn_clients_id_city_foreign')->references(['id_locations'])->on('locations');
            $table->foreign(['id_client_channel'], 'nvn_clients_id_client_channel_foreign')->references(['id_channel'])->on('dist_channels');
            $table->foreign(['id_client_type'], 'nvn_clients_id_client_type_foreign')->references(['id_type'])->on('client_types')->cascadeOnDelete();
            $table->foreign(['id_department'], 'nvn_clients_id_department_foreign')->references(['id_locations'])->on('locations');
            $table->foreign(['id_diab_contact'], 'nvn_clients_id_diab_contact_foreign')->references(['id'])->on('users');
            $table->foreign(['id_payterm'], 'nvn_clients_id_payterm_fkey')->references(['id_payterms'])->on('payment_terms');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
