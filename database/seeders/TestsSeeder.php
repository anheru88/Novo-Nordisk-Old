<?php

namespace Database\Seeders;

use Database\Seeders\LocalSeeders\AdditionalTermSeeder;
use Database\Seeders\LocalSeeders\BrandSeeder;
use Database\Seeders\LocalSeeders\ClientFilesSeeder;
use Database\Seeders\LocalSeeders\ClientSeeder;
use Database\Seeders\LocalSeeders\ClientTypeSeeder;
use Database\Seeders\LocalSeeders\DistChannelSeeder;
use Database\Seeders\LocalSeeders\DocumentsSeeder;
use Database\Seeders\LocalSeeders\LocationSeeder;
use Database\Seeders\LocalSeeders\NegotiationConceptSeeder;
use Database\Seeders\LocalSeeders\PaymentTermSeeder;
use Database\Seeders\LocalSeeders\PriceListSeeder;
use Database\Seeders\LocalSeeders\ProductLineSeeder;
use Database\Seeders\LocalSeeders\ProductMeasureUnitSeeder;
use Database\Seeders\LocalSeeders\ProductSeeder;
use Database\Seeders\LocalSeeders\UserSeeder;
use Illuminate\Database\Seeder;

class TestsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(DocumentsSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(AdditionalTermSeeder::class);
        $this->call(NegotiationConceptSeeder::class);
        $this->call(ProductMeasureUnitSeeder::class);
        $this->call(ProductLineSeeder::class);
        $this->call(PaymentTermSeeder::class);
        $this->call(ClientTypeSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(DistChannelSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(ClientFilesSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(PriceListSeeder::class);
    }
}
