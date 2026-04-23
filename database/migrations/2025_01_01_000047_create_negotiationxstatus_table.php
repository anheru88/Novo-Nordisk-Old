<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('negotiationxstatus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('negotiation_id');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['negotiation_id'], 'nvn_negotiationxstatus_negotiation_id_foreign')->references(['id_negotiation'])->on('negotiations')->cascadeOnDelete();
            $table->foreign(['status_id'], 'nvn_negotiationxstatus_status_id_foreign')->references(['status_id'])->on('status')->cascadeOnDelete();
            $table->foreign(['user_id'], 'nvn_negotiationxstatus_user_id_foreign')->references(['id'])->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negotiationxstatus');
    }
};
