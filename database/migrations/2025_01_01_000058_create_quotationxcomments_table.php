<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quotationxcomments', function (Blueprint $table) {
            $table->bigIncrements('id_quotationxcomments');
            $table->unsignedInteger('id_quotation')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->text('type_comment')->nullable();
            $table->text('text_comment')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['id_quotation'], 'nvn_quotationxcomments_id_quotation_foreign')->references(['id_quotation'])->on('quotations')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotationxcomments');
    }
};
