<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClientFilesSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('client_files')->truncate();

        foreach (array_chunk($this->files(), 200) as $chunk) {
            DB::table('client_files')->insert($chunk);
        }

        Schema::enableForeignKeyConstraints();
    }

    /** @return array<int, array<string, mixed>> */
    protected function files(): array
    {
        return require __DIR__.'/data/client_files_files.php';
    }
}
