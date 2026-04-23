<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('negotiationxdocs', function (Blueprint $table) {
            $table->bigIncrements('id_negotiationxdocs');
            $table->unsignedInteger('id_negotiation')->nullable();
            $table->string('doc_name', 255)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->string('file_folder', 255);
            $table->foreign(['id_negotiation'], 'nvn_negotiationxdocs_id_negotiation_foreign')->references(['id_negotiation'])->on('negotiations')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negotiationxdocs');
    }
};
