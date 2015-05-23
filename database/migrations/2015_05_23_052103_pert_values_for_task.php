<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PertValuesForTask extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('tasks', function (Blueprint $table) {
			$table->integer('optimistic_duration')->unsigned();
			$table->integer('pessimistic_duration')->unsigned();
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
		Schema::table('tasks', function (Blueprint $table) {			
			$table->dropColumn('optimistic_duration');
			$table->dropColumn('pessimistic_duration');
		});
	}

}
