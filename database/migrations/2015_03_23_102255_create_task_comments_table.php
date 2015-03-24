<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('task_comments', function (Blueprint $table) {
			$table->increments('id');
			$table->string('comment', 400);
			$table->timestamp('archived_at')->nullable();

			$table->integer('created_by')->unsigned();
			$table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

			$table->integer('task_id')->unsigned();
			$table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');

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
		Schema::drop('task_comments');
	}

}
