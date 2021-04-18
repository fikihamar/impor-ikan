<?php

use App\Models\FishMerchant;
use Illuminate\Database\Seeder;

class FishMerchantSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			FishMerchant::create([
				'quantity' => ($i + 1) * 10,
				'merchant_id' => $i + 1,
				'fish_id' => $i + 1,
			]);
		}
	}
}
