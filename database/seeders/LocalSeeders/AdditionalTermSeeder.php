<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdditionalTermSeeder extends Seeder
{
    /**
     * Additional product uses (Usos adicionales).
     *
     * Source: legacy Produccion dump, table public.nvn_aditional_terms
     * (id_use → id, use_name → name).
     *
     * @var array<int, array{id:int,name:string,created_at:string,updated_at:string}>
     */
    protected array $terms = [
        ['id' => 2, 'name' => 'INSUMO', 'created_at' => '2020-01-22 19:41:50', 'updated_at' => '2020-01-22 19:41:50'],
    ];

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('additional_terms')->truncate();

        DB::table('additional_terms')->insert($this->terms);

        Schema::enableForeignKeyConstraints();
    }
}
