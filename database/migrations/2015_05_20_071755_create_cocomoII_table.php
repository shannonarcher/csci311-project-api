<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCocomoIITable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('cocomoII', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('project_id')->unsigned();

			$table->decimal('PREC', 10, 2)->default(0.00);
			$table->decimal('FLEX', 10, 2)->default(0.00);
			$table->decimal('RESL', 10, 2)->default(0.00);
			$table->decimal('TEAM', 10, 2)->default(0.00);
			$table->decimal('PMAT', 10, 2)->default(0.00);

			$table->decimal('RCPX', 10, 2)->default(0.00);
			$table->decimal('RUSE', 10, 2)->default(0.00);
			$table->decimal('PDIF', 10, 2)->default(0.00);
			$table->decimal('PERS', 10, 2)->default(0.00);
			$table->decimal('PREX', 10, 2)->default(0.00);
			$table->decimal('FCIL', 10, 2)->default(0.00);
			$table->decimal('SCED', 10, 2)->default(0.00);
			
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
		Schema::drop('cocomoII');
	}

}
