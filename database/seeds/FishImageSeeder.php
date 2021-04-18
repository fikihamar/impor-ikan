<?php

use App\Models\FishImage;
use Illuminate\Database\Seeder;

class FishImageSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			FishImage::create([
				'filename' => "fishimage_$i.jpg",
				'main_image' => 1,
				'fish_id' => $i + 1
			]);
		}
	}
}
