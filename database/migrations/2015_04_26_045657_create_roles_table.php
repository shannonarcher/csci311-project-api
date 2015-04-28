<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//


		Schema::create('roles', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
		});

		Schema::create('users_roles', function (Blueprint $table) {
			$table->increments('id')->unsigned();

			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');

			$table->integer('assigned_by')->unsigned();
			$table->foreign('assigned_by')->references('id')->on('users');

			$table->integer('assigned_for')->unsigned();
			$table->foreign('assigned_for')->references('id')->on('projects');

			$table->integer('role_id')->unsigned();
			$table->foreign('role_id')->references('id')->on('roles');
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
		Schema::drop('roles');
		Schema::drop('users_roles');
	}

}
