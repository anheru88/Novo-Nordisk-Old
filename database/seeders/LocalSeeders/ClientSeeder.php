<?php

namespace Database\Seeders\LocalSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = require __DIR__.'/data/clients.php';

        $validUsers = DB::table('users')->pluck('id')->all();
        $validTypes = DB::table('client_types')->pluck('id')->all();
        $validChannels = DB::table('dist_channels')->pluck('id')->all();
        $validPayterms = DB::table('payment_terms')->pluck('id')->all();
        $validLocations = DB::table('locations')->pluck('id')->all();

        $fallbackUser = DB::table('users')->value('id');
        $fallbackType = DB::table('client_types')->value('id');
        $fallbackChannel = DB::table('dist_channels')->value('id');

        $rows = array_map(static function (array $c) use (
            $validUsers, $validTypes, $validChannels, $validPayterms, $validLocations,
            $fallbackUser, $fallbackType, $fallbackChannel,
        ): array {
            if (! in_array($c['client_type_id'], $validTypes, true)) {
                $c['client_type_id'] = $fallbackType;
            }
            if (! in_array($c['client_channel_id'], $validChannels, true)) {
                $c['client_channel_id'] = $fallbackChannel;
            }
            if ($c['payterm_id'] !== null && ! in_array($c['payterm_id'], $validPayterms, true)) {
                $c['payterm_id'] = null;
            }
            if ($c['department_id'] !== null && ! in_array($c['department_id'], $validLocations, true)) {
                $c['department_id'] = null;
            }
            if ($c['city_id'] !== null && ! in_array($c['city_id'], $validLocations, true)) {
                $c['city_id'] = null;
            }
            if (! in_array($c['created_by'], $validUsers, true)) {
                $c['created_by'] = $fallbackUser;
            }
            if (! in_array($c['diab_contact_id'], $validUsers, true)) {
                $c['diab_contact_id'] = $fallbackUser;
            }
            if ($c['biof_contact_id'] !== null && ! in_array($c['biof_contact_id'], $validUsers, true)) {
                $c['biof_contact_id'] = null;
            }

            return $c;
        }, $clients);

        Schema::disableForeignKeyConstraints();

        DB::table('clients')->truncate();

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('clients')->insert($chunk);
        }

        Schema::enableForeignKeyConstraints();
    }
}
