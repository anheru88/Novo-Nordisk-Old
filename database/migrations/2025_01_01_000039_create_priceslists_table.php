<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('priceslists', function (Blueprint $table) {
            $table->bigIncrements('id_pricelists');
            $table->unsignedBigInteger('id_authorizer_user');
            $table->text('list_version');
            $table->text('active')->nullable()->default(0);
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->text('comments')->nullable();
            $table->text('list_name')->nullable();
            $table->foreign(['id_authorizer_user'], 'nvn_priceslists_id_authorizer_user_foreign')->references(['id'])->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('priceslists');
    }
};
