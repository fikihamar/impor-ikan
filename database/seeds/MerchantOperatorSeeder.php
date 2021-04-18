<?php

use App\Models\MerchantOperator;
use Illuminate\Database\Seeder;

class MerchantOperatorSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			MerchantOperator::create([
				'name' => "Merchant Operator $i",
				'email' => "merchant_operator_$i@mail.com",
				'password' => bcrypt('admin123'),
				'default_password' => 1,
				'merchant_id' => $i + 1
			]);
		}
	}
}
