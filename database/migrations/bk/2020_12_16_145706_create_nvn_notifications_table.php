<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvnNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvn_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('destiny_id');
            $table->unsignedBigInteger('sender_id');
            $table->text('type')->nullable();
            $table->text('data')->nullable();
            $table->text('url')->nullable();
            $table->integer('readed')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->foreign('destiny_id')->references('id')->on('users');
            $table->foreign('sender_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nvn_notifications');
    }
}
