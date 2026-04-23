<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('arp_service', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('services_arp_id');
            $table->unsignedBigInteger('brand_id');
            $table->decimal('volume', 30, 2)->nullable();
            $table->decimal('value_cop', 30, 2)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['brand_id'], 'nvn_arp_service_brand_id_foreign')->references(['id_brand'])->on('brands');
            $table->foreign(['services_arp_id'], 'nvn_arp_service_services_arp_id_foreign')->references(['id'])->on('services_arp')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arp_service');
    }
};
