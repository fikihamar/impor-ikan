<?php

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			City::create([
				'name' => "City $i",
				'province_id' => $i + 1
			]);
		}
	}
}
