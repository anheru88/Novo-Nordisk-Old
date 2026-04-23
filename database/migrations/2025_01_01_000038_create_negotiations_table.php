<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('negotiations', function (Blueprint $table) {
            $table->increments('id_negotiation');
            $table->unsignedInteger('id_client');
            $table->unsignedInteger('id_city')->nullable();
            $table->integer('is_authorized')->nullable()->default(0);
            $table->unsignedBigInteger('id_authorizer_user')->nullable();
            $table->unsignedInteger('id_channel')->nullable();
            $table->text('id_auth_level')->nullable();
            $table->timestamp('negotiation_date_ini', 0);
            $table->timestamp('negotiation_date_end', 0);
            $table->integer('pre_approved')->nullable()->default(0);
            $table->text('comments')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->text('negotiation_consecutive')->nullable();
            $table->text('negotiation_number')->nullable();
            $table->integer('status_id')->default(0);
            $table->text('pdf_content')->nullable();
            $table->foreign(['created_by'], 'nvn_negotiations_created_by_foreign')->references(['id'])->on('users');
            $table->foreign(['id_authorizer_user'], 'nvn_negotiations_id_authorizer_user_foreign')->references(['id'])->on('users');
            $table->foreign(['id_channel'], 'nvn_negotiations_id_channel_foreign')->references(['id_channel'])->on('dist_channels');
            $table->foreign(['id_city'], 'nvn_negotiations_id_city_foreign')->references(['id_locations'])->on('locations');
            $table->foreign(['id_client'], 'nvn_negotiations_id_client_foreign')->references(['id_client'])->on('clients')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negotiations');
    }
};
