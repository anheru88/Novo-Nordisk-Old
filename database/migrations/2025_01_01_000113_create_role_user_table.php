<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->index(['role_id'], 'role_user_role_id_index');
            $table->index(['user_id'], 'role_user_user_id_index');
            $table->foreign(['role_id'], 'role_user_role_id_foreign')->references(['id'])->on('roles')->cascadeOnDelete();
            $table->foreign(['user_id'], 'role_user_user_id_foreign')->references(['id'])->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
