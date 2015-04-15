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
		$user2 = User::where('is_admin', '=', 0)->first();

		$project1 = Project::create([
				'name' => 'CSCI311',
				'created_by' => $user->id,
				'started_at' => new DateTime('@'.strtotime('+3 days')),
				'expected_completed_at' => new DateTime('@'.strtotime('+30 days'))
			]);

		$project2 = Project::create([
				'name' => 'CSCI312',
				'created_by' => $user->id,
				'started_at' => new DateTime('@'.strtotime('-3 days')),
				'expected_completed_at' => new DateTime('@'.strtotime('+60 days'))
			]);

		$project1->users()->attach($user2, ['is_manager'=>false]);
		$project2->users()->attach($user2, ['is_manager'=>true]);
	}

}