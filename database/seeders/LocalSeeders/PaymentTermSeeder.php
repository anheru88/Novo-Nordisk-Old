<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PaymentTermSeeder extends Seeder
{
    /**
     * Payment terms (Métodos de pago).
     *
     * Source: legacy Produccion dump, table public.nvn_payment_terms
     * (id_payterms → id, payterm_name → name, payterm_percent → percent,
     * payterm_code → code).
     *
     * @var array<int, array{id:int,name:string,percent:string,code:?string,created_at:string,updated_at:string}>
     */
    protected array $terms = [
        ['id' => 7,  'name' => '90 dias neto',                                        'percent' => '0.00', 'code' => 'F090', 'created_at' => '2019-07-18 15:21:58', 'updated_at' => '2020-02-24 20:57:51'],
        ['id' => 8,  'name' => '30 dias 5% desc.,60 dias 3% desc., 90 dias neto',    'percent' => '5.00', 'code' => '0095', 'created_at' => '2019-07-18 15:21:58', 'updated_at' => '2025-06-12 13:56:55'],
        ['id' => 9,  'name' => 'Venta de contado 5% desc.',                           'percent' => '5.00', 'code' => '0065', 'created_at' => '2019-07-18 15:21:58', 'updated_at' => '2020-02-27 21:49:34'],
        ['id' => 18, 'name' => 'VENTA DE CONTADO 0%',                                 'percent' => '0.00', 'code' => '0001', 'created_at' => '2021-03-09 17:03:53', 'updated_at' => '2021-03-09 17:03:53'],
        ['id' => 19, 'name' => '30 dias 5% desc.',                                    'percent' => '5.00', 'code' => '0036', 'created_at' => '2025-06-12 12:31:00', 'updated_at' => '2025-06-12 13:57:15'],
        ['id' => 20, 'name' => '30 dias 5% desc.,60 dias 3% desc.',                   'percent' => '5.00', 'code' => '0063', 'created_at' => '2025-06-12 13:47:39', 'updated_at' => '2025-06-12 13:47:39'],
    ];

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('payment_terms')->truncate();

        DB::table('payment_terms')->insert($this->terms);

        Schema::enableForeignKeyConstraints();
    }
}
