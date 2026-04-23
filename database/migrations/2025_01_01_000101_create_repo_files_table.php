<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('repo_files', function (Blueprint $table) {
            $table->integer('id_files');
            $table->integer('id_parent')->nullable();
            $table->text('file_folder')->nullable();
            $table->text('file_name')->nullable();
            $table->date('created_at')->nullable();
            $table->date('updated_at')->nullable();
            $table->primary('id_files');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repo_files');
    }
};
