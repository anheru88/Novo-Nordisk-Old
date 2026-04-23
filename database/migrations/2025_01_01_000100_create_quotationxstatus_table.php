<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quotationxstatus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('quotation_id');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['quotation_id'], 'nvn_quotationxstatus_quotation_id_foreign')->references(['id_quotation'])->on('quotations');
            $table->foreign(['status_id'], 'nvn_quotationxstatus_status_id_foreign')->references(['status_id'])->on('status');
            $table->foreign(['user_id'], 'nvn_quotationxstatus_user_id_foreign')->references(['id'])->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotationxstatus');
    }
};
