<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BrandSeeder extends Seeder
{
    /**
     * Novo Nordisk product brands used across the portfolio.
     *
     * @var array<int, array{id:int,name:string}>
     */
    protected array $brands = [
        ['id' => 1,  'name' => 'NOVOLIN N®'],
        ['id' => 2,  'name' => 'NOVOLIN R®'],
        ['id' => 3,  'name' => 'GLUCAGEN®'],
        ['id' => 4,  'name' => 'LEVEMIR®'],
        ['id' => 5,  'name' => 'NOVORAPID®'],
        ['id' => 6,  'name' => 'TRESIBA®'],
        ['id' => 7,  'name' => 'XULTOPHY®'],
        ['id' => 8,  'name' => 'VICTOZA®'],
        ['id' => 9,  'name' => 'OZEMPIC®'],
        ['id' => 10, 'name' => 'SAXENDA®'],
        ['id' => 11, 'name' => 'NOVOSEVEN®'],
        ['id' => 12, 'name' => 'NOVOEIGHT®'],
        ['id' => 13, 'name' => 'NORDITROPIN®'],
    ];

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('brands')->truncate();

        $now = now();
        $rows = array_map(static fn (array $brand): array => [
            'id'         => $brand['id'],
            'name'       => $brand['name'],
            'created_at' => $now,
            'updated_at' => $now,
        ], $this->brands);

        DB::table('brands')->insert($rows);

        Schema::enableForeignKeyConstraints();
    }
}
