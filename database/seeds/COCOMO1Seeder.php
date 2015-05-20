<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\SystemType;

class COCOMO1Seeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		SystemType::create([
			'name' => "Organic (Information System)",
			'c' => 2.5,
			'k' => 1.05 ]);

		SystemType::create([
			'name' => "Semi-detached",
			'c' => 3,
			'k' => 1.12 ]);

		SystemType::create([
			'name' => "Embedded (Real time)",
			'c' => 3.6,
			'k' => 1.2 ]);
	}

}