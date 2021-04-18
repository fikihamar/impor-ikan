<?php

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			Transaction::create([
				'code' => Str::random(10),
				'address' => "Example Address $i",
				'proof_image' => "proof_image.jpg",
				'status' => 'OP',
				'merchant_id' => $i + 1,
				'client_id' => $i + 1
			]);
		}
	}
}
