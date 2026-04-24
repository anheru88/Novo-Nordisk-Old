<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NegotiationConceptSeeder extends Seeder
{
    /**
     * Negotiation concepts catalog (Conceptos de negociación).
     *
     * Source: legacy Produccion dump, table public.nvn_negotiation_concepts
     * (id_negotiation_concepts → id, name_concept → name).
     *
     * @var array<int, array{id:int,name:string,concept_percentage:?string,concept_compress:?int,sap_concept:?string,created_at:string,updated_at:string}>
     */
    protected array $concepts = [
        ['id' => 1,  'name' => 'INFORMACIÓN',              'concept_percentage' => '1',  'concept_compress' => null, 'sap_concept' => 'NC INFORMACIÓN',           'created_at' => '2020-06-17 15:47:24', 'updated_at' => '2022-08-31 17:10:17'],
        ['id' => 2,  'name' => 'PRESENTACIÓN pack x 3',    'concept_percentage' => '1',  'concept_compress' => null, 'sap_concept' => 'NC PRESENTACIÓN PACK X 3', 'created_at' => '2020-06-18 19:30:24', 'updated_at' => '2022-08-31 17:10:25'],
        ['id' => 3,  'name' => 'COMERCIAL',                'concept_percentage' => '1',  'concept_compress' => 0,    'sap_concept' => null,                       'created_at' => '2020-06-18 19:30:37', 'updated_at' => '2020-12-11 20:45:55'],
        ['id' => 4,  'name' => 'CONVENIO',                 'concept_percentage' => '1',  'concept_compress' => null, 'sap_concept' => null,                       'created_at' => '2020-06-18 19:30:49', 'updated_at' => '2020-12-23 22:49:51'],
        ['id' => 5,  'name' => 'CODIFICACIÓN',             'concept_percentage' => '1',  'concept_compress' => 0,    'sap_concept' => null,                       'created_at' => '2020-06-18 19:30:58', 'updated_at' => '2020-09-07 20:31:35'],
        ['id' => 6,  'name' => 'PAGO',                     'concept_percentage' => '1',  'concept_compress' => 0,    'sap_concept' => null,                       'created_at' => '2020-06-18 19:31:05', 'updated_at' => '2020-06-18 19:31:05'],
        ['id' => 7,  'name' => 'PROVISIÓN',                'concept_percentage' => '1',  'concept_compress' => null, 'sap_concept' => null,                       'created_at' => '2020-06-18 19:31:10', 'updated_at' => '2020-12-23 22:49:48'],
        ['id' => 8,  'name' => 'CORTA EXPIRA',             'concept_percentage' => '1',  'concept_compress' => 0,    'sap_concept' => null,                       'created_at' => '2020-06-18 19:31:18', 'updated_at' => '2020-06-18 19:31:18'],
        ['id' => 10, 'name' => 'NEGOCIACIÓN ESPECIAL',     'concept_percentage' => '1',  'concept_compress' => 0,    'sap_concept' => null,                       'created_at' => '2020-07-22 22:51:06', 'updated_at' => '2020-09-07 20:33:21'],
        ['id' => 11, 'name' => 'ADHERENCIA',               'concept_percentage' => '1',  'concept_compress' => 0,    'sap_concept' => null,                       'created_at' => '2020-12-10 20:30:41', 'updated_at' => '2020-12-10 20:30:41'],
        ['id' => 13, 'name' => 'LOGISTICA',                'concept_percentage' => null, 'concept_compress' => null, 'sap_concept' => null,                       'created_at' => '2021-02-18 17:54:52', 'updated_at' => '2021-02-18 17:54:52'],
        ['id' => 14, 'name' => 'LANZAMIENTO',              'concept_percentage' => null, 'concept_compress' => null, 'sap_concept' => null,                       'created_at' => '2021-02-18 17:55:05', 'updated_at' => '2021-02-18 17:55:05'],
        ['id' => 15, 'name' => 'CAPITA',                   'concept_percentage' => null, 'concept_compress' => null, 'sap_concept' => null,                       'created_at' => '2021-06-25 18:33:24', 'updated_at' => '2021-06-25 18:33:24'],
        ['id' => 16, 'name' => 'DISTRIBUCION NUMERICA',    'concept_percentage' => '1',  'concept_compress' => null, 'sap_concept' => 'DISTRIBUCION NUMERICA',    'created_at' => '2025-10-21 14:49:10', 'updated_at' => '2025-10-21 14:49:10'],
    ];

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('negotiation_concepts')->truncate();

        DB::table('negotiation_concepts')->insert($this->concepts);

        Schema::enableForeignKeyConstraints();
    }
}
