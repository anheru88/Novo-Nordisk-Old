<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductLineSeeder extends Seeder
{
    /**
     * Product line categories (Líneas de producto).
     *
     * Source: legacy Produccion dump, table public.nvn_product_lines
     * (id_line → id, line_name → name).
     *
     * @var array<int, array{id:int,name:string,created_at:string,updated_at:string}>
     */
    protected array $lines = [
        ['id' => 1, 'name' => 'Insulinas',         'created_at' => '2019-07-18 15:16:16', 'updated_at' => '2020-02-26 14:52:24'],
        ['id' => 2, 'name' => 'Biofarma',          'created_at' => '2019-07-18 15:16:16', 'updated_at' => '2019-07-18 15:16:16'],
        ['id' => 4, 'name' => 'GLP 1 - Obesidad',  'created_at' => '2020-01-22 18:34:36', 'updated_at' => '2020-02-26 16:58:22'],
        ['id' => 5, 'name' => 'GLP 1 - Diabetes',  'created_at' => '2020-01-24 12:38:16', 'updated_at' => '2020-02-26 16:58:28'],
        ['id' => 6, 'name' => 'Dispositivos',      'created_at' => '2020-01-24 12:38:28', 'updated_at' => '2020-02-26 14:53:24'],
    ];

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('product_lines')->truncate();

        DB::table('product_lines')->insert($this->lines);

        Schema::enableForeignKeyConstraints();
    }
}
