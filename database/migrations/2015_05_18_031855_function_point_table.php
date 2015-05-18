<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FunctionPointTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('function_points', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('project_id')->unsigned();

			$table->integer('low_ilf')->unsigned();
			$table->integer('med_ilf')->unsigned();
			$table->integer('hi_ilf')->unsigned();

			$table->integer('low_eif')->unsigned();
			$table->integer('med_eif')->unsigned();
			$table->integer('hi_eif')->unsigned();

			$table->integer('low_ei')->unsigned();
			$table->integer('med_ei')->unsigned();
			$table->integer('hi_ei')->unsigned();

			$table->integer('low_eo')->unsigned();
			$table->integer('med_eo')->unsigned();
			$table->integer('hi_eo')->unsigned();

			$table->integer('low_eq')->unsigned();
			$table->integer('med_eq')->unsigned();
			$table->integer('hi_eq')->unsigned();

			$table->integer('gsc_1')->unsigned();
			$table->integer('gsc_2')->unsigned();
			$table->integer('gsc_3')->unsigned();
			$table->integer('gsc_4')->unsigned();
			$table->integer('gsc_5')->unsigned();
			$table->integer('gsc_6')->unsigned();
			$table->integer('gsc_7')->unsigned();
			$table->integer('gsc_8')->unsigned();
			$table->integer('gsc_9')->unsigned();
			$table->integer('gsc_10')->unsigned();
			$table->integer('gsc_11')->unsigned();
			$table->integer('gsc_12')->unsigned();
			$table->integer('gsc_13')->unsigned();
			$table->integer('gsc_14')->unsigned();

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
		Schema::drop('function_points');
	}

}
