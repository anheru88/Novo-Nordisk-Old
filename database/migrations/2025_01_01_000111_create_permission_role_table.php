<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->index(['permission_id'], 'permission_role_permission_id_index');
            $table->index(['role_id'], 'permission_role_role_id_index');
            $table->foreign(['permission_id'], 'permission_role_permission_id_foreign')->references(['id'])->on('permissions')->cascadeOnDelete();
            $table->foreign(['role_id'], 'permission_role_role_id_foreign')->references(['id'])->on('roles')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_role');
    }
};
