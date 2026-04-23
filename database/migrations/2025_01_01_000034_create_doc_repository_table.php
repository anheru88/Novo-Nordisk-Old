<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('doc_repository', function (Blueprint $table) {
            $table->increments('id_doc');
            $table->string('doc_name', 191)->nullable();
            $table->unsignedInteger('id_folder')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['id_folder'], 'nvn_doc_repository_id_folder_foreign')->references(['id_folder'])->on('folder_repository')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doc_repository');
    }
};
