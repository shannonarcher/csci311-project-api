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
			'email' => 'john@company.com',
			'password' => Hash::make("admin"),
			'is_admin' => 1
		]);
		$jane = User::create([
			'name' => 'Jennifer Citizen',
			'email' => 'j@c',
			'password' => Hash::make("aoeuhtns"),
			'is_admin' => 1
		]);
	}

}