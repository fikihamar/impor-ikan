<?php

use App\Models\Operator;
use Illuminate\Database\Seeder;

class OperatorSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			Operator::create([
				'name' => "Operator $i",
				'email' => "operator_$i@mail.com",
				'password' => bcrypt('operator123')
			]);
		}
	}
}
