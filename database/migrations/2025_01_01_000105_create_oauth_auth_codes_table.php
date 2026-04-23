<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('oauth_auth_codes', function (Blueprint $table) {
            $table->string('id', 100);
            $table->bigInteger('user_id');
            $table->bigInteger('client_id');
            $table->text('scopes')->nullable();
            $table->boolean('revoked');
            $table->timestamp('expires_at', 0)->nullable();
            $table->primary('id');
            $table->index(['user_id'], 'oauth_auth_codes_user_id_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oauth_auth_codes');
    }
};
