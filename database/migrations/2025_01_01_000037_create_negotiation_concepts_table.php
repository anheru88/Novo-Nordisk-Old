<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('negotiation_concepts', function (Blueprint $table) {
            $table->bigIncrements('id_negotiation_concepts');
            $table->text('name_concept')->nullable();
            $table->text('concept_percentage')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->integer('concept_compress')->nullable()->default(0);
            $table->string('sap_concept', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negotiation_concepts');
    }
};
