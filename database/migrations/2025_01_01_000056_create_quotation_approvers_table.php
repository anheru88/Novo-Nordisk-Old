<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quotation_approvers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('answer', 255)->nullable();
            $table->unsignedInteger('quotation_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['quotation_id'], 'nvn_quotation_approvers_quotation_id_foreign')->references(['id_quotation'])->on('quotations')->cascadeOnDelete();
            $table->foreign(['user_id'], 'nvn_quotation_approvers_user_id_foreign')->references(['id'])->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_approvers');
    }
};
