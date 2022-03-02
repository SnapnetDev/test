<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpicommentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('kpicomments', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('kpi_id');
				$table->string('lm_comment');
				$table->string('emp_comment');
				$table->integer('emp_id');
				$table->string('from');
				$table->string('to');
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
		}
};
		