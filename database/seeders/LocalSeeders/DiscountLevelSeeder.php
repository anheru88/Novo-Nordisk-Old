<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DiscountLevelSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [
            ['id' => 1, 'name' => 'Nivel 1', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Nivel 2', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Nivel 3', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Nivel 4', 'created_at' => $now, 'updated_at' => $now],
        ];

        Schema::disableForeignKeyConstraints();

        DB::table('discount_levels')->truncate();

        DB::table('discount_levels')->insert($rows);

        Schema::enableForeignKeyConstraints();
    }
}
