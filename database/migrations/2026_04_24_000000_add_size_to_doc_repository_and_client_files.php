<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doc_repository', function (Blueprint $table) {
            if (! Schema::hasColumn('doc_repository', 'size')) {
                $table->unsignedBigInteger('size')->nullable()->after('folder_id');
            }
        });

        Schema::table('client_files', function (Blueprint $table) {
            if (! Schema::hasColumn('client_files', 'size')) {
                $table->unsignedBigInteger('size')->nullable()->after('file_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('doc_repository', function (Blueprint $table) {
            if (Schema::hasColumn('doc_repository', 'size')) {
                $table->dropColumn('size');
            }
        });

        Schema::table('client_files', function (Blueprint $table) {
            if (Schema::hasColumn('client_files', 'size')) {
                $table->dropColumn('size');
            }
        });
    }
};
