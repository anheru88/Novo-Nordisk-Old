<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = require __DIR__.'/data/brands.php';

        Schema::disableForeignKeyConstraints();

        DB::table('brands')->truncate();

        DB::table('brands')->insert($brands);

        Schema::enableForeignKeyConstraints();
    }
}
