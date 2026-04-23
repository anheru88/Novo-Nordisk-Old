<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id_locations');
            $table->integer('loc_codedep');
            $table->text('loc_name');
            $table->integer('loc_codecity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
