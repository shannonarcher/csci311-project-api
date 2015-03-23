<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('user_roles', function(Blueprint $table) 
		{
			$table->increments('id');
			$table->string('name', 30);
			$table->timestamps();
		});

		Schema::table('users', function ($table) 
		{
			$table->integer('role_id')->unsigned();
			$table->foreign('role_id')->references('id')->on('user_roles');
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
		Schema::drop('user_roles');

		Schema::table('users', function ($table) 
		{
			$table->dropForeign('role_id');
			$table->dropColumn('role_id');
		});
	}

}
