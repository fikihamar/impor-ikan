<?php

use App\Models\ClientVerification;
use Illuminate\Database\Seeder;

class ClientVerificationSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			ClientVerification::create([
				'email' => "example_$i@mail.com",
				'otp' => strtoupper(Str::random(6))
			]);
		}
	}
}
