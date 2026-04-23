<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('arp_business_case', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('arp_id');
            $table->unsignedBigInteger('brand_id');
            $table->decimal('pbc', 30, 2)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->bigInteger('service_arp_id');
            $table->foreign(['arp_id'], 'nvn_arp_business_case_arp_id_foreign')->references(['id'])->on('arps')->cascadeOnDelete();
            $table->foreign(['brand_id'], 'nvn_arp_business_case_brand_id_foreign')->references(['id_brand'])->on('brands');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arp_business_case');
    }
};
