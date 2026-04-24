<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PriceListSeeder extends Seeder
{
    public function run(): void
    {
        $rows = require __DIR__.'/data/price_lists.php';

        $validUsers = DB::table('users')->pluck('id')->all();
        $fallbackUser = DB::table('users')->value('id');

        $rows = array_map(static function (array $r) use ($validUsers, $fallbackUser): array {
            if (! in_array($r['authorizer_user_id'], $validUsers, true)) {
                $r['authorizer_user_id'] = $fallbackUser;
            }

            return $r;
        }, $rows);

        Schema::disableForeignKeyConstraints();

        DB::table('price_lists')->truncate();

        DB::table('price_lists')->insert($rows);

        Schema::enableForeignKeyConstraints();
    }
}
