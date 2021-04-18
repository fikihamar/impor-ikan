<?php

use App\Models\Fish;
use Illuminate\Database\Seeder;

class FishSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			Fish::create([
				'name' => "Fish $i",
				'description' => "Fish Description $i",
				'type' => "FRESH",
				'price' => ($i + 1) * 100000, 
				'min_order' => ($i + 1) * 10000, 
			]);
		}
	}
}
