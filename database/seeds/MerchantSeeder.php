<?php

use App\Models\Merchant;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			Merchant::create([
				'name' => "Merchant $i",
				'description' => "Description Merchant $i",
				'address' => "Address Merchant $i",
				'phone_number' => "01230000$i",
				'bank' => random_int(10, 20),
				'operator_id' => $i + 1,
				'city_id' => $i + 1
			]);
		}
	}
}
