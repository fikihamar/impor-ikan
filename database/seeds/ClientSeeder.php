<?php

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			Client::create([
				'name' => "Client $i",
				'email' => "client_$i@mail.com",
				'password' => bcrypt("admin123"),
				'phone_number' => "01230000$i",
				'address' => "Client Address $i"
			]);
		}
	}
}
