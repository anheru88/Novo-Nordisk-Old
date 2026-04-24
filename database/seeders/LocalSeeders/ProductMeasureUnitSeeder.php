<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductMeasureUnitSeeder extends Seeder
{
    /**
     * Product measurement units (Unidades de venta).
     *
     * Source: legacy Produccion dump, table public.nvn_product_measure_units
     * (id_unit → id, unit_name → name).
     *
     * @var array<int, array{id:int,name:string,created_at:string,updated_at:string}>
     */
    protected array $units = [
        ['id' => 11, 'name' => 'MG',        'created_at' => '2019-07-18 15:21:58', 'updated_at' => '2019-07-18 15:21:58'],
        ['id' => 12, 'name' => 'UI',        'created_at' => '2019-07-18 15:21:58', 'updated_at' => '2019-07-18 15:21:58'],
        ['id' => 13, 'name' => 'PACKS',     'created_at' => '2019-07-18 15:21:58', 'updated_at' => '2019-07-18 15:21:58'],
        ['id' => 14, 'name' => 'UNIDADES',  'created_at' => '2019-07-18 15:21:58', 'updated_at' => '2019-07-18 15:21:58'],
        ['id' => 15, 'name' => 'CAJAS',     'created_at' => '2019-07-18 15:21:58', 'updated_at' => '2019-07-18 15:21:58'],
        ['id' => 16, 'name' => 'Aguja',     'created_at' => '2020-02-25 22:37:42', 'updated_at' => '2020-02-25 22:37:42'],
    ];

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('product_measure_units')->truncate();

        DB::table('product_measure_units')->insert($this->units);

        Schema::enableForeignKeyConstraints();
    }
}
