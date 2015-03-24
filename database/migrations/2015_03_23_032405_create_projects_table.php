<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('projects', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100);
			$table->timestamp('archived_at')->nullable();

			$table->integer('created_by')->unsigned();
			$table->foreign('created_by')->references('id')->on('users');

			$table->timestamps();
		});

		Schema::create('project_users', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

			$table->boolean('is_manager')->default(0);
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
		Schema::drop('project_users');
		Schema::drop('projects');
	}

}
