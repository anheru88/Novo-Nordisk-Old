<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('oauth_refresh_tokens', function (Blueprint $table) {
            $table->string('id', 100);
            $table->string('access_token_id', 100);
            $table->boolean('revoked');
            $table->timestamp('expires_at', 0)->nullable();
            $table->primary('id');
            $table->index(['access_token_id'], 'oauth_refresh_tokens_access_token_id_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oauth_refresh_tokens');
    }
};
