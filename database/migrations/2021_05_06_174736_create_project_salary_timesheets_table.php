<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectSalaryTimesheetsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('project_salary_timesheets', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('pace_salary_component_id');
				$table->integer('user_id');
				$table->integer('days')->nullable();
				$table->string('for')->nullable();
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
		