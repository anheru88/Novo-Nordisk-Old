<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('arp_clients', function (Blueprint $table) {
            $table->increments('id_client');
            $table->string('client_name', 191);
            $table->string('client_sap_code', 191);
            $table->integer('id_client_channel');
            $table->integer('id_client_type');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arp_clients');
    }
};
