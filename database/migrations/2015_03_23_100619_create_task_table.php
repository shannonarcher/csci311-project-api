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

			$table->timestamp('approved_at')->nullable();			
			$table->integer('approved_by')->unsigned()->nullable();

			$table->integer('created_by')->unsigned();
			$table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

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
