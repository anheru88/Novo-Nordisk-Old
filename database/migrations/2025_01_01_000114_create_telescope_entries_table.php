<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('telescope_entries', function (Blueprint $table) {
            $table->bigIncrements('sequence');
            $table->uuid('uuid');
            $table->uuid('batch_id');
            $table->string('family_hash', 255)->nullable();
            $table->boolean('should_display_on_index')->default(true);
            $table->string('type', 20);
            $table->text('content');
            $table->timestamp('created_at', 0)->nullable();
            $table->unique(['uuid'], 'telescope_entries_uuid_unique');
            $table->index(['batch_id'], 'telescope_entries_batch_id_index');
            $table->index(['created_at'], 'telescope_entries_created_at_index');
            $table->index(['family_hash'], 'telescope_entries_family_hash_index');
            $table->index(['type', 'should_display_on_index'], 'telescope_entries_type_should_display_on_index_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telescope_entries');
    }
};
