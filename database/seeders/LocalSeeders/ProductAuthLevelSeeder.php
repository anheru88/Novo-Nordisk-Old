<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductAuthLevelSeeder extends Seeder
{
    public function run(): void
    {
        $rows = require __DIR__.'/data/product_auth_levels.php';

        $validProducts = DB::table('products')->pluck('id')->all();
        $validChannels = DB::table('dist_channels')->pluck('id')->all();
        $validLevels = DB::table('discount_levels')->pluck('id')->all();
        $validLists = DB::table('price_lists')->pluck('id')->all();

        $rows = array_values(array_filter($rows, static fn (array $r): bool => in_array($r['product_id'], $validProducts, true)
            && in_array($r['dist_channel_id'], $validChannels, true)
            && in_array($r['level_discount_id'], $validLevels, true)
            && ($r['pricelists_id'] === null || in_array($r['pricelists_id'], $validLists, true))));

        Schema::disableForeignKeyConstraints();

        DB::table('product_auth_levels')->truncate();

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('product_auth_levels')->insert($chunk);
        }

        Schema::enableForeignKeyConstraints();
    }
}
