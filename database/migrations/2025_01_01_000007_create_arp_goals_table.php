<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('arp_goals', function (Blueprint $table) {
            $table->increments('id_goal');
            $table->string('prod_sap_code', 191);
            $table->text('goal_name');
            $table->decimal('goal_quantity', 10, 2);
            $table->decimal('goal_value', 10, 2);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->decimal('goal_quantity_com', 10, 2)->nullable();
            $table->decimal('goal_value_com', 10, 2)->nullable();
            $table->decimal('goal_quantity_ins', 10, 2)->nullable();
            $table->decimal('goal_value_ins', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arp_goals');
    }
};
