<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Role;
use App\User;
use App\Project;
use App\Milestone;

class MilestoneTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

		$user = User::first();
		$user2 = User::where('is_admin', '=', '0')->first();

		$project1 = Project::first();

		$project1->milestones()->save(new Milestone([
			'title' => str_random(20),
			'completed_at' => new DateTime('@'.strtotime('+3 days')),
			'created_by' => $user->id
			]));

		$project1->milestones()->save(new Milestone([
			'title' => str_random(20),
			'completed_at' => new DateTime('@'.strtotime('+9 days')),
			'created_by' => $user->id
			]));

		$project1->milestones()->save(new Milestone([
			'title' => str_random(20),
			'completed_at' => new DateTime('@'.strtotime('+15 days')),
			'created_by' => $user->id
			]));
	}

}