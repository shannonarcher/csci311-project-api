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

		DB::table('projects')->truncate();
		DB::table('users')->truncate();
		DB::table('tasks')->truncate();
		DB::table('milestones')->truncate();
		DB::table('skills')->truncate();

		DB::statement(DB::raw("SET foreign_key_checks = 1;"));

		$this->call('UserTableSeeder');
		/*$this->call('ProjectTableSeeder');
		$this->call('TaskTableSeeder');
		$this->call('MilestoneTableSeeder');*/

		$this->call('TestDataSeeder');
	}

}