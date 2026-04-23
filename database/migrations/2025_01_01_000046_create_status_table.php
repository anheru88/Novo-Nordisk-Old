<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('status', function (Blueprint $table) {
            $table->bigIncrements('status_id');
            $table->string('status_name', 255)->nullable();
            $table->string('status_color', 255)->nullable();
            $table->string('status_symbol', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status');
    }
};
