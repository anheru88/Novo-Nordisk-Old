<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductPriceSeeder extends Seeder
{
    public function run(): void
    {
        $rows = require __DIR__.'/data/product_prices.php';

        $validProducts = DB::table('products')->pluck('id')->all();
        $validLists = DB::table('price_lists')->pluck('id')->all();

        $rows = array_values(array_filter($rows, static fn (array $r): bool => in_array($r['product_id'], $validProducts, true)
            && in_array($r['pricelists_id'], $validLists, true)));

        Schema::disableForeignKeyConstraints();

        DB::table('product_prices')->truncate();

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('product_prices')->insert($chunk);
        }

        Schema::enableForeignKeyConstraints();
    }
}
