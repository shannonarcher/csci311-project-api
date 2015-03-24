<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Role;
use App\User;

class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$admin = Role::create(['name'=>'admin']);
		$member = Role::create(['name'=>'member']);

		$john = User::create([
			'name' => 'John Public',
			'email' => 'john.public@example.com',
			'password' => Hash::make(str_random(20))
		]);
		$john->roles()->attach($admin);

		$jane = User::create([
			'name' => 'Jane Public',
			'email' => 'jane.public@example.com',
			'password' => Hash::make(str_random(20))
		]);
		$jane->roles()->attach($member);

		$sam = User::create([
			'name' => 'Sam Public',
			'email' => 'sam.public@example.com',
			'password' => Hash::make(str_random(20))
		]);
		$sam->roles()->attach($member);

	}

}