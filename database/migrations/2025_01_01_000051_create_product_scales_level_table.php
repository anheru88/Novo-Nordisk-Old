<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_scales_level', function (Blueprint $table) {
            $table->bigIncrements('id_scale_level');
            $table->unsignedInteger('id_scale');
            $table->string('scale_discount', 255);
            $table->string('scale_min', 255);
            $table->string('scale_max', 255);
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->text('version')->nullable();
            $table->unsignedInteger('id_measure_unit')->nullable();
            $table->foreign(['id_measure_unit'], 'nvn_product_scales_level_id_measure_unit_fkey')->references(['id_unit'])->on('product_measure_units');
            $table->foreign(['id_scale'], 'nvn_product_scales_level_id_scale_foreign')->references(['id_scale'])->on('product_scales')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_scales_level');
    }
};
