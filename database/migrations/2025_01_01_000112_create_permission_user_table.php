<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('permission_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('permission_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->index(['permission_id'], 'permission_user_permission_id_index');
            $table->index(['user_id'], 'permission_user_user_id_index');
            $table->foreign(['permission_id'], 'permission_user_permission_id_foreign')->references(['id'])->on('permissions')->cascadeOnDelete();
            $table->foreign(['user_id'], 'permission_user_user_id_foreign')->references(['id'])->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_user');
    }
};
