<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DistChannelSeeder extends Seeder
{
    public function run(): void
    {
        $channels = require __DIR__.'/data/dist_channels.php';

        Schema::disableForeignKeyConstraints();

        DB::table('dist_channels')->truncate();

        DB::table('dist_channels')->insert($channels);

        Schema::enableForeignKeyConstraints();
    }
}
