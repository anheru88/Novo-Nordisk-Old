<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->string('slug', 191);
            $table->text('description')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->string('special', 255)->nullable();
            $table->unique(['name'], 'roles_name_unique');
            $table->unique(['slug'], 'roles_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
