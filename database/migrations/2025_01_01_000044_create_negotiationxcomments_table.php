<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('negotiationxcomments', function (Blueprint $table) {
            $table->bigIncrements('id_negotiationxcomments');
            $table->unsignedInteger('id_negotiation')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->text('type_comment')->nullable();
            $table->text('text_comment')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['id_negotiation'], 'nvn_negotiationxcomments_id_negotiation_foreign')->references(['id_negotiation'])->on('negotiations')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negotiationxcomments');
    }
};
