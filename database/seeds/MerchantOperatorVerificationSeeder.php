<?php

use App\Models\MerchantOperatorVerification;
use Illuminate\Database\Seeder;

class MerchantOperatorVerificationSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			MerchantOperatorVerification::create([
				'email' => "example_$i@mail.com",
				'otp' => strtoupper(Str::random(6))
			]);
		}
	}
}
