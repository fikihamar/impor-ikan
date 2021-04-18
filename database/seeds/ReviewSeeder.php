<?php

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			Review::create([
				'star' => 5,
				'description' => "Description $i",
				'merchant_id' => $i + 1,
				'client_id' => $i + 1
			]);
		}
	}
}
