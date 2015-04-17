<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('tasks', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title', 100);
			$table->string('description', 1500);
			$table->timestamp('started_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('estimation_duration', 20)->default('');
			$table->timestamp('completed_at')->nullable();
			$table->integer('progress')->default(0);

			$table->timestamp('approved_at')->nullable();			
			$table->integer('approved_by')->unsigned()->nullable();

			$table->integer('parent_id')->unsigned()->nullable();

			$table->integer('created_by')->unsigned();
			$table->foreign('created_by')->references('id')->on('users');

			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')->references('id')->on('projects');

			$table->timestamps();
		});

		Schema::create('task_dependencies', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('independent_id')->unsigned();
			$table->foreign('independent_id')->references('id')->on('tasks');

			$table->integer('dependent_id')->unsigned();
			$table->foreign('dependent_id')->references('id')->on('tasks');

			$table->timestamps();
		});

		Schema::create('task_resources', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');

			$table->integer('task_id')->unsigned();
			$table->foreign('task_id')->references('id')->on('users');

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
		Schema::drop('tasks');
	}

}
