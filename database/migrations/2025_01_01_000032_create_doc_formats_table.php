<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('doc_formats', function (Blueprint $table) {
            $table->bigIncrements('id_format');
            $table->unsignedBigInteger('id_formattype');
            $table->text('name')->nullable();
            $table->text('body')->nullable();
            $table->text('conditions_time')->nullable();
            $table->text('conditions_content')->nullable();
            $table->text('conditions_special')->nullable();
            $table->text('terms_title')->nullable();
            $table->text('terms_content')->nullable();
            $table->text('sign_name')->nullable();
            $table->text('sign_charge')->nullable();
            $table->text('sign_image')->nullable();
            $table->text('footer')->nullable();
            $table->text('active')->default(1);
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['id_formattype'], 'nvn_doc_formats_id_formattype_foreign')->references(['id_formattype'])->on('doc_format_types');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doc_formats');
    }
};
