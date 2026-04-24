<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClientTypeSeeder extends Seeder
{
    /**
     * Client types (Tipos de cliente).
     *
     * Source: legacy Produccion dump, table public.nvn_client_types
     * (id_type → id, type_name → name). created_at was NULL in the
     * legacy dump and is normalised to the updated_at value.
     *
     * @var array<int, array{id:int,name:string,created_at:string,updated_at:string}>
     */
    protected array $types = [
        ['id' => 1, 'name' => 'Droguerias / comercial', 'created_at' => '2020-05-11 21:46:25', 'updated_at' => '2020-05-11 21:46:25'],
        ['id' => 2, 'name' => 'OL / DistribuidorES',    'created_at' => '2020-05-11 21:55:06', 'updated_at' => '2020-05-11 21:55:06'],
        ['id' => 3, 'name' => 'Gestores Farmaceuticos', 'created_at' => '2020-05-11 21:44:00', 'updated_at' => '2020-05-11 21:44:00'],
        ['id' => 4, 'name' => 'HTC / IPS',              'created_at' => '2020-05-11 21:44:40', 'updated_at' => '2020-05-11 21:44:40'],
    ];

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('client_types')->truncate();

        DB::table('client_types')->insert($this->types);

        Schema::enableForeignKeyConstraints();
    }
}
