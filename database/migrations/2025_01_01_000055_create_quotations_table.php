<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('id_quotation');
            $table->unsignedInteger('id_client');
            $table->unsignedInteger('id_city');
            $table->integer('is_authorized')->default(1);
            $table->unsignedBigInteger('id_authorizer_user')->nullable();
            $table->unsignedInteger('id_channel');
            $table->integer('quota_value')->nullable();
            $table->timestamp('quota_date_ini', 0);
            $table->timestamp('quota_date_end', 0);
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->string('id_auth_level')->nullable();
            $table->integer('pre_aproved')->nullable()->default(0);
            $table->text('comments')->nullable();
            $table->text('quota_number')->nullable();
            $table->text('quota_consecutive')->nullable();
            $table->text('comments_auth')->nullable();
            $table->text('comments_pre')->nullable();
            $table->bigInteger('status_id')->default(0);
            $table->foreign(['created_by'], 'nvn_quotations_created_by_foreign')->references(['id'])->on('users');
            $table->foreign(['id_authorizer_user'], 'nvn_quotations_id_authorizer_user_foreign')->references(['id'])->on('users');
            $table->foreign(['id_channel'], 'nvn_quotations_id_channel_foreign')->references(['id_channel'])->on('dist_channels');
            $table->foreign(['id_city'], 'nvn_quotations_id_city_foreign')->references(['id_locations'])->on('locations');
            $table->foreign(['id_client'], 'nvn_quotations_id_client_foreign')->references(['id_client'])->on('clients')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
