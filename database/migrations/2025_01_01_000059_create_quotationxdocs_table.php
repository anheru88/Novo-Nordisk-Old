<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quotationxdocs', function (Blueprint $table) {
            $table->bigIncrements('id_quotationxdoc');
            $table->unsignedInteger('id_quotation')->nullable();
            $table->string('doc_name', 255)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->text('file_folder')->nullable();
            $table->foreign(['id_quotation'], 'nvn_quotationxdocs_id_quotation_foreign')->references(['id_quotation'])->on('quotations')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotationxdocs');
    }
};
