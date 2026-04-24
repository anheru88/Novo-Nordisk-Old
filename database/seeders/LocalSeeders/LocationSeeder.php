<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = require __DIR__.'/data/locations.php';

        Schema::disableForeignKeyConstraints();

        DB::table('locations')->truncate();

        foreach (array_chunk($locations, 500) as $chunk) {
            DB::table('locations')->insert($chunk);
        }

        Schema::enableForeignKeyConstraints();
    }
}
