<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('doc_status', function (Blueprint $table) {
            $table->increments('id_status');
            $table->text('status_name');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->string('status_color', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doc_status');
    }
};
