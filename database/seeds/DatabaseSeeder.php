<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(OperatorVerificationSeeder::class);
        $this->call(MerchantOperatorVerificationSeeder::class);
        $this->call(ClientVerificationSeeder::class);
        $this->call(OperatorSeeder::class);
        $this->call(ProvinceSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(MerchantSeeder::class);
        $this->call(MerchantOperatorSeeder::class);
        $this->call(MerchantImageSeeder::class);
        $this->call(FishSeeder::class);
        $this->call(FishImageSeeder::class);
        $this->call(FishMerchantSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(CartSeeder::class);
        $this->call(TransactionSeeder::class);
        $this->call(TransactionDetailSeeder::class);
        $this->call(ReviewSeeder::class);
    }
}
