<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;

class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$john = User::create([
			'name' => 'John Public',
			'email' => 'john@liquidly.com',
			'password' => Hash::make("admin"),
			'is_admin' => 1
		]);

		$jane = User::create([
			'name' => 'Jane Public',
			'email' => 'jane@liquidly.com',
			'password' => Hash::make(str_random(20))
		]);

		$sam = User::create([
			'name' => 'Sam Public',
			'email' => 'sam@liquidly.com',
			'password' => Hash::make(str_random(20))
		]);

	}

}