<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Milestone;
use App\Project;
use App\Task;
use App\TaskComment;
use App\Skill;

class TestDataSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		try {
			$admins = [];
			$users  = [];
			$projects = [];
			$milestones = [];
			$tasks = [];
			$subtasks = [];

			$admin_distribution = 2;

			$num_users = 10 * 20;
			$num_projects = 5;
			$max_num_milestones = 6;
			$min_num_tasks = 2;
			$max_num_tasks = 12;
			$max_num_subtasks = 4;

			// USERS
			$first_names_filename = "database/seeds/data/first_names.txt";
			$last_names_filename = "database/seeds/data/last_names.txt";
			$skills_filename = "database/seeds/data/skills.txt";
			$admin_pct = 5;


			// read in names
			$first_names = file($first_names_filename, FILE_IGNORE_NEW_LINES);
			$last_names  = file($last_names_filename, FILE_IGNORE_NEW_LINES);
			$skills = file($skills_filename, FILE_IGNORE_NEW_LINES);

			for ($i = 0; $i < $num_users; $i++) {

				$is_admin = (rand(0, 100) < $admin_distribution);

				$fname = $first_names[rand(0, count($first_names)-1)];
				$lname = $last_names[rand(0, count($last_names)-1)];
				$emailHandle = strtolower($fname.'.'.$lname);

				try {
					$user = User::create([
						"name" => "$fname $lname",
						"email" => "$emailHandle@company.com",
						"password" => Hash::make(str_random(8)),
						"is_admin" => $is_admin
					]);
				} catch (Exception $ex) {
					$i--;
					echo "Oops, duplicate email generated. \n";
					echo $ex->getLine() .': '. $ex->getMessage();
					continue;
				}

				if ($is_admin)
					array_push($admins, $user);
				else
					array_push($users, $user);

				$num_skills = rand(5, 20);
				for ($j = 0; $j < $num_skills; $j++) {
					$user->skills()->save(new Skill([
						'name' => $skills[rand(0, count($skills)-1)]
						]));
				}
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
					echo $ex->getLine() .': '. $ex->getMessage();
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
					$milestone = null;
					try {
						$milestone = new Milestone([
							"title" => "Milestone $j",
							"completed_at" => new DateTime('@'.($start + rand(0, $range))),
							"created_by" => $project_managers[rand(0, count($project_managers)-1)]->id
						]);

						$project->milestones()->save($milestone);

						array_push($milestones, $milestone);
					} catch (Exception $ex) {
						$j--;
						echo "\tOops, something went wrong creating a milestone. \n";
						echo ($ex->getMessage());
					}
				}
			}

			echo "Milestones added. \n";

			// TASKS	
			$sentences_filename = "database/seeds/data/sentences.txt";
			$sentences = file($sentences_filename, FILE_IGNORE_NEW_LINES);
	
			for ($i = 0; $i < $num_projects; $i++) {
				try {
					$project = $projects[$i];
				} catch (Exception $ex) {
					echo "\tOops, something went wrong selecting a project. \n";
				}

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

					try {

						$parent = $project->tasks()->save(new Task([
									'title' => $sentences[rand(0, count($sentences)-1)], 
									'description' => $sentences[rand(0, count($sentences)-1)] . ' ' . 
													 $sentences[rand(0, count($sentences)-1)] . ' ' .
													 $sentences[rand(0, count($sentences)-1)] . ' ' .
													 $sentences[rand(0, count($sentences)-1)],  
									'started_at' => new DateTime('@'.$t_start), 
									'estimation_duration' => $t_durat, 
									'approved_by' => $project_managers[rand(0, count($project_managers)-1)]->id, 
									'approved_at' => new DateTime('now'),
									'created_by' => $project_users[rand(0, count($project_users)-1)]->id,
									'progress' => rand(0, 100)
								]));

						$project->load('tasks');
						$tasks = $project->tasks;
						if (count($tasks) > 1) {
							$task = $tasks[rand(0, count($tasks)-1)];
							if ($task->id != $parent->id) {
								$parent->dependencies()->attach($task);
							}
						}

						$this->addComments($parent, $project);

					} catch (Exception $ex) {
						echo "\tOops, something went wrong inserting parent task.";
						echo $ex->getMessage();
						$j--;
						continue;
					}

					// create 0 to x subtasks for task
					$num_subtasks = rand(0, $max_num_subtasks);
					$t_range = $t_durat;
					for ($h = 0; $h < $num_subtasks; $h++) {
						try {
							$task = new Task([
								'title' => $sentences[rand(0, count($sentences)-1)], 
								'description' => $sentences[rand(0, count($sentences)-1)] . ' ' . 
												 $sentences[rand(0, count($sentences)-1)] . ' ' .
												 $sentences[rand(0, count($sentences)-1)] . ' ' .
												 $sentences[rand(0, count($sentences)-1)],  
								'started_at' => new DateTime('@'.($t_start + rand(0, $t_range))), 
								'estimation_duration' => rand(2, 10) * 86400, 
								'approved_by' => $project_managers[rand(0, count($project_managers)-1)]->id, 
								'approved_at' => new DateTime('now'),
								'created_by' => $project_users[rand(0, count($project_users)-1)]->id,
								'parent_id' => $parent->id,
								'progress' => rand(0, 100)
								]);
							$project->tasks()->save($task);

							$this->addComments($task, $project);
						} catch (Exception $ex) {
							$h--;
							echo "\tOops, something went wrong inserting sub task.";
							echo $ex->getMessage();
						}

					}
				}
			}

			echo "Tasks and comments added.\n";

		} catch (Exception $ex) {
			echo $ex->getLine() . ': ' . $ex->getMessage();
		}
	}

	public function addComments($task, $project) 
	{
		$project_users = $project->users;

		$max_num_comments = 5;

		$sentences_filename = "database/seeds/data/sentences.txt";
		$sentences = file($sentences_filename, FILE_IGNORE_NEW_LINES);

		$num_comments = rand(0, $max_num_comments);
		for ($i = 0; $i < $num_comments; $i++) {
			$comment = new TaskComment([
				'comment' => $sentences[rand(0, count($sentences)-1)],
				'created_by' => $project_users[rand(0, count($project_users)-1)]->id
				]);

			$task->comments()->save($comment);
		}

		// assign a couple project users to task
		$num_resources = rand(1, count($project_users)-1);
		for ($i = 0; $i < $num_resources; $i++) {
			$task->resources()->attach($project_users[rand(0, count($project_users)-1)]);
		}
	}

}