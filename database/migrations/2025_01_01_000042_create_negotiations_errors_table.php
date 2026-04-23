<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('negotiations_errors', function (Blueprint $table) {
            $table->bigIncrements('id_negotiations_errors');
            $table->unsignedInteger('id_negotiation_det');
            $table->text('negotiation_error');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign(['id_negotiation_det'], 'nvn_negotiations_errors_id_negotiation_det_foreign')->references(['id_negotiation_det'])->on('negotiations_details')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negotiations_errors');
    }
};
