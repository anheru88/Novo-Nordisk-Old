<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('format_certificates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_formattype');
            $table->string('country', 255);
            $table->string('reference', 255);
            $table->string('header_body', 255);
            $table->text('body');
            $table->string('footer_body', 255);
            $table->string('user_firm', 255);
            $table->string('user_name', 255);
            $table->string('user_position', 255);
            $table->string('page_name', 255);
            $table->string('footer_column1_1', 255);
            $table->string('footer_column1_2', 255);
            $table->string('footer_column1_3', 255);
            $table->string('footer_column2_1', 255);
            $table->string('footer_column3_1', 255);
            $table->string('active', 255);
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['id_formattype'], 'nvn_format_certificates_id_formattype_foreign')->references(['id_formattype'])->on('doc_format_types')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('format_certificates');
    }
};
