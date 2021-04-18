<?php

use App\Models\TransactionDetail;
use Illuminate\Database\Seeder;

class TransactionDetailSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			TransactionDetail::create([
				'quantity' => ($i + 1) * 10,
				'transaction_id' => $i + 1,
				'fish_merchant_id' => $i + 1
			]);
		}
	}
}
