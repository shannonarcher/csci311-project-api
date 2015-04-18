<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 100);
			$table->string('email', 255)->unique();
			$table->string('password', 60);
			$table->string('session_token', 32);
			
			$table->boolean('is_admin')->default(0);
			$table->boolean('is_archived')->default(0);

			$table->string('lang', 5)->default('en');

			$table->rememberToken();
			$table->timestamps();
		});

		Schema::create('user_skills', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');

			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');

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
		Schema::drop('users');
	}

}
