<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('telescope_entries_tags', function (Blueprint $table) {
            $table->uuid('entry_uuid');
            $table->string('tag', 255);
            $table->index(['entry_uuid', 'tag'], 'telescope_entries_tags_entry_uuid_tag_index');
            $table->index(['tag'], 'telescope_entries_tags_tag_index');
            $table->foreign(['entry_uuid'], 'telescope_entries_tags_entry_uuid_foreign')->references(['uuid'])->on('telescope_entries')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telescope_entries_tags');
    }
};
