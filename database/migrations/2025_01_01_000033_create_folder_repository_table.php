<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('folder_repository', function (Blueprint $table) {
            $table->increments('id_folder');
            $table->string('folder_name', 191)->nullable();
            $table->string('folder_url', 191)->nullable();
            $table->integer('id_parent')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('folder_repository');
    }
};
