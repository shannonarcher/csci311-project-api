<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Milestone;
use App\Project;
use App\Task;

class TestDataSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$admins = [];
		$users  = [];
		$projects = [];
		$milestones = [];
		$tasks = [];
		$subtasks = [];

		$num_users = 20; // 27 * 20;
		$num_projects = 2; // 10;
		$max_num_milestones = 1; // 6;
		$min_num_tasks = 5;
		$max_num_tasks = 30;
		$max_num_subtasks = 4;

		// USERS
		$first_names_filename = "database/seeds/data/first_names.txt";
		$last_names_filename = "database/seeds/data/last_names.txt";
		$admin_pct = 5;


		// read in names
		$first_names = file($first_names_filename, FILE_IGNORE_NEW_LINES);
		$last_names  = file($last_names_filename, FILE_IGNORE_NEW_LINES);

		for ($i = 0; $i < $num_users; $i++) {


			$is_admin = (rand(0, 100) < 5);

			$fname = $first_names[rand(0, count($first_names)-1)];
			$lname = $last_names[rand(0, count($last_names)-1)];

			try {
				$user = User::create([
					"name" => "$fname $lname",
					"email" => "$fname.$lname@company.com",
					"password" => Hash::make(str_random(8)),
					"is_admin" => $is_admin
				]);
			} catch (Exception $ex) {
				$i--;
				echo "Oops, duplicate email generated. \n";
				echo $ex->message . '\n';
				continue;
			}

			if ($is_admin)
				array_push($admins, $user);
			else
				array_push($users, $user);
		}

		echo "Users added. \n";

		// PROJECTS
		$project_names_filename = "database/seeds/data/project_names.txt";
		$project_names = file($project_names_filename, FILE_IGNORE_NEW_LINES);

		for ($i = 0; $i < $num_projects; $i++) {
			try {
				$project = Project::create([
					"name" => $project_names[rand(0, count($project_names)-1)],
					"created_by" => $admins[rand(0, count($admins)-1)]->id,
					"started_at" => new DateTime('@'.strtotime('+'. rand(0, 90) . ' days')),
					"expected_completed_at" => new DateTime('@'.strtotime('+'. rand(90, 365) .' days')),
				]);

				// attach a manager
				$project->users()->attach($users[rand(0, count($users)-1)]->id, ["is_manager" => true]);

				// attach random amount of users
				$team_count = rand(7, 49);
				for ($j = 0; $j < $team_count; $j++) {
					$project->users()->attach($users[rand(0, count($users)-1)]->id);
				}

				array_push($projects, $project);
			} catch (Exception $ex) {
				$i--;
				echo "\tOops, something went wrong creating a project. \n";
			} 
			finally {
				if ($project) {
					$project->delete();
					$project->users()->detach();
				}
			}
		}

		echo "Projects added. \n";

		// MILESTONES
		for ($i = 0; $i < $num_projects; $i++) {
			try {
				$project = $projects[$i];
				$project_managers = $project->managers;

				$start = strtotime($project->started_at->format('Y-m-d H:i:s'));
				$end   = strtotime($project->expected_completed_at->format('Y-m-d H:i:s'));
			} catch (Exception $ex) {
				$i--;
				echo "\tOops, something went wrong finding a project. \n";
				continue;
			}

			$range = $end - $start;
			$num_milestones = rand($max_num_milestones / 2, $max_num_milestones);

			for ($j = 0; $j < $num_milestones; $j++) {
				try {
					$milestone = new Milestone([
						"title" => "Milestone $j",
						"completed_at" => new DateTime('@'.($start + rand(0, $range))),
						"created_by" => $project_managers[rand(0, count($project_managers)-1)]
					]);

					$project->milestones()->save($milestone);

					array_push($milestones, $milestone);
				} catch (Exception $ex) {
					$j--;
					if ($milestone) {
						$milestone->delete();
					}
					echo "\tOops, something went wrong creating a milestone. \n";
				var_dump($ex);
				die();
				}
			}
		}

		echo "Milestones added. \n";

		// TASKS	
		$sentences_filename = "database/seeds/data/sentences.txt";
		$sentences = file($sentences_filename, FILE_IGNORE_NEW_LINES);

		for ($i = 0; $i < $num_projects; $i++) {
			$project = $projects[$i];

			$start = strtotime($project->started_at->format('Y-m-d H:i:s'));
			$end   = strtotime($project->expected_completed_at->format('Y-m-d H:i:s'));

			$range = $end - $start;

			// create x - y tasks for project
			$num_tasks = rand($min_num_tasks, $max_num_tasks);
			for ($j = 0; $j < $num_tasks; $j++) {
				$project_managers = $project->managers;
				$project_users = $project->users;

				$t_start = ($start + rand(0, $range));
				$t_durat = rand(2, 10) * 86400;

				$parent = $project->tasks()->save(new Task([
							'title' => $sentences[rand(0, count($sentences))], 
							'description' => $sentences[rand(0, count($sentences))] . ' ' . 
											 $sentences[rand(0, count($sentences))] . ' ' .
											 $sentences[rand(0, count($sentences))] . ' ' .
											 $sentences[rand(0, count($sentences))],  
							'started_at' => new DateTime('@'.$t_start), 
							'estimation_duration' => $t_durat, 
							'approved_by' => $project_managers[rand(0, count($project_managers))], 
							'approved_at' => new DateTime('now'),
							'created_by' => $project_users[rand(0, count($project_users))]
						]));

				// create 0 to x subtasks for task
				$num_subtasks = rand(0, $max_num_subtasks);
				for ($h = 0; $h < $num_subtasks; $h++) {
					$task = new Task([
							'title' => $sentences[rand(0, count($sentences))], 
							'description' => $sentences[rand(0, count($sentences))] . ' ' . 
											 $sentences[rand(0, count($sentences))] . ' ' .
											 $sentences[rand(0, count($sentences))] . ' ' .
											 $sentences[rand(0, count($sentences))],  
							'started_at' => new DateTime('@'.($start + rand(0, $range))), 
							'estimation_duration' => rand(2, 10) * 86400, 
							'approved_by' => $project_managers[rand(0, count($project_managers))], 
							'approved_at' => new DateTime('now'),
							'created_by' => $project_users[rand(0, count($project_users))],
							'parent_id' => $parent->id
							]);
					$project->tasks()->save($task);
				}
			}
		}

		echo "Tasks created";

	}

}