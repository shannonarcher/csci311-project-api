<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilestoneTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('milestones', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title', 100)->default('');
			$table->timestamp('completed_at');
			
			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')->references('id')->on('projects');

			$table->timestamps();
		});

		Schema::create('milestone_tasks', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('task_id')->unsigned();
			$table->foreign('task_id')->references('id')->on('tasks');

			$table->integer('milestone_id')->unsigned();
			$table->foreign('milestone_id')->references('id')->on('milestones');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('milestones');
		Schema::drop('milestone_tasks');
	}

}
