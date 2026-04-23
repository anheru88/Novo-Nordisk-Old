<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services_arp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->default('AB');
            $table->unsignedBigInteger('arps_id');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->unique(['name'], 'nvn_services_arp_name_unique');
            $table->foreign(['arps_id'], 'nvn_services_arp_arps_id_foreign')->references(['id'])->on('arps')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services_arp');
    }
};
