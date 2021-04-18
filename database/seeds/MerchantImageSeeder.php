<?php

use App\Models\MerchantImage;
use Illuminate\Database\Seeder;

class MerchantImageSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			MerchantImage::create([
				'filename' => "image_$i.jpg",
				'main_image' => 1,
				'merchant_id' => $i + 1
			]);
		}
	}
}
