<?php

use App\Models\Cart;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 3; $i < 11; $i++) {
			Cart::create([
				'quantity' => ($i + 1) * 10,
				'fish_merchant_id' => $i, 
				'client_id' => 1
			]);
		}
	}
}
