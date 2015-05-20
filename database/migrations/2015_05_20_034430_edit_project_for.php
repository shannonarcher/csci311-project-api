<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// Edit project for cocomo 1
class EditProjectFor extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('projects', function (Blueprint $table) {
			$table->decimal('kloc', 10, 2);
			$table->integer('system_type_id')->unsigned()->default(0);
		});

		Schema::create('system_types', function (Blueprint $table) {
			$table->increments('id');

			$table->string('name', 50);
			$table->decimal('c', 10, 2);
			$table->decimal('k', 10, 2);

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
		Schema::drop('system_types');
		Schema::table('projects', function ($table) {
			$table->dropColumn('kloc');
			$table->dropColumn('system_type_id');
		});
	}

}
