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
	}

}