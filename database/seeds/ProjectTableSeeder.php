<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Role;
use App\User;
use App\Project;

class ProjectTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$user = User::first();
		$user2 = User::whereHas('roles', function ($q) {
			$q->where('name', '=', 'member');
		})->first();

		$project1 = Project::create([
				'name' => 'CSCI311',
				'created_by' => $user->id
			]);

		$project2 = Project::create([
				'name' => 'CSCI312',
				'created_by' => $user->id
			]);

		$project1->users()->attach($user2, ['is_manager'=>false]);
		$project2->users()->attach($user2, ['is_manager'=>true]);
	}

}