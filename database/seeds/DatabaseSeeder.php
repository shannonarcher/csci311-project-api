<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();
		
		DB::statement(DB::raw("SET foreign_key_checks = 0;"));

		DB::table('cocomoii')->truncate();
		DB::table('function_points')->truncate();
		DB::table('milestones')->truncate();
		DB::table('projects')->truncate();
		DB::table('project_users')->truncate();
		DB::table('roles')->truncate();
		DB::table('skills')->truncate();
		DB::table('system_types')->truncate();
		DB::table('tasks')->truncate();
		DB::table('task_comments')->truncate();
		DB::table('task_dependencies')->truncate();
		DB::table('task_resources')->truncate();
		DB::table('users')->truncate();
		DB::table('users_roles')->truncate();
		DB::table('users_skills')->truncate();

		DB::statement(DB::raw("SET foreign_key_checks = 1;"));

		$this->call('UserTableSeeder');
		/*$this->call('ProjectTableSeeder');
		$this->call('TaskTableSeeder');
		$this->call('MilestoneTableSeeder');*/

		$this->call('TestDataSeeder');
		$this->call('COCOMO1Seeder');
	}

}