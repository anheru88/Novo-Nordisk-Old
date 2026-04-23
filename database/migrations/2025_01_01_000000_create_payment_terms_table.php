<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_terms', function (Blueprint $table) {
            $table->increments('id_payterms');
            $table->text('payterm_name');
            $table->decimal('payterm_percent', 10, 2);
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->text('payterm_code')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_terms');
    }
};
