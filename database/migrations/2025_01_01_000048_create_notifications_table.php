<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('destiny_id');
            $table->unsignedBigInteger('sender_id');
            $table->text('type')->nullable();
            $table->text('data')->nullable();
            $table->text('url')->nullable();
            $table->integer('readed')->nullable();
            $table->timestamp('read_at', 0)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->string('column8', 50)->nullable();
            $table->foreign(['destiny_id'], 'nvn_notifications_destiny_id_foreign')->references(['id'])->on('users');
            $table->foreign(['sender_id'], 'nvn_notifications_sender_id_foreign')->references(['id'])->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
