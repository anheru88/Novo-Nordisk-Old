<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = require __DIR__.'/data/products.php';

        $validUsers = DB::table('users')->pluck('id')->all();
        $validLines = DB::table('product_lines')->pluck('id')->all();
        $validUnits = DB::table('product_measure_units')->pluck('id')->all();
        $validBrands = DB::table('brands')->pluck('id')->all();

        $fallbackUser = DB::table('users')->value('id');

        $rows = array_map(static function (array $p) use ($validUsers, $validLines, $validUnits, $validBrands, $fallbackUser): array {
            if (! in_array($p['created_by'], $validUsers, true)) {
                $p['created_by'] = $fallbackUser;
            }
            if ($p['prod_line_id'] !== null && ! in_array($p['prod_line_id'], $validLines, true)) {
                $p['prod_line_id'] = null;
            }
            if ($p['measure_unit_id'] !== null && ! in_array($p['measure_unit_id'], $validUnits, true)) {
                $p['measure_unit_id'] = null;
            }
            if ($p['brand_id'] !== null && ! in_array($p['brand_id'], $validBrands, true)) {
                $p['brand_id'] = null;
            }

            return $p;
        }, $products);

        Schema::disableForeignKeyConstraints();

        DB::table('products')->truncate();

        foreach (array_chunk($rows, 50) as $chunk) {
            DB::table('products')->insert($chunk);
        }

        Schema::enableForeignKeyConstraints();
    }
}
