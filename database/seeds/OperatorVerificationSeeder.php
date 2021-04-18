<?php

use App\Models\OperatorVerification;
use Illuminate\Database\Seeder;

class OperatorVerificationSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			OperatorVerification::create([
				'email' => "example_$i@mail.com",
				'otp' => strtoupper(Str::random(6))
			]);
		}
	}
}
