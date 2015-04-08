<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Role;
use App\User;
use App\Project;
use App\Task;
use App\TaskComment;

class TaskTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$user = User::first();
		$user2 = User::where('is_admin', '=', 0)->first();

		$project1 = Project::first();

		$project1->tasks()->save(new Task([
			'title' => "Initialize Project", 
			'description' => "Create git repo and initial laravel project", 
			'started_at' => new DateTime('@'.strtotime('+3 days')), 
			'estimation_duration' => "P5D", 
			'approved_by' => $user->id, 
			'approved_at' => new DateTime('now'),
			'created_by' => $user2->id
			]));
		$project1->tasks()->save(new Task([			
			'title' => "Develop migrations based on ERD", 
			'description' => "Using ERD 1.0 create database migrations using eloquent ORM", 
			'started_at' => strtotime('+7 days'), 
			'estimation_duration' => "P5D", 
			'approved_by' => $user->id, 
			'approved_at' => strtotime('now'),
			'created_by' => $user2->id
			]));

		$tasks = $project1->tasks;
		foreach ($tasks as $task) {
			$task->comments()->save(new TaskComment([
				'comment' => str_random(100),
				'created_by' => $user2->id
				]));
		}
	}

}