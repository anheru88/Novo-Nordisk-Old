<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clients_files', function (Blueprint $table) {
            $table->increments('id_files');
            $table->unsignedInteger('id_client');
            $table->string('file_folder', 191)->nullable();
            $table->string('file_name', 191)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['id_client'], 'nvn_clients_files_id_client_foreign')->references(['id_client'])->on('clients')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients_files');
    }
};
