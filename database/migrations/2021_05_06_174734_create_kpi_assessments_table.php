<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiAssessmentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('kpi_assessments', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->integer('fiscal_year');
				$table->integer('kpi_id');
				$table->integer('user_score')->default('0');
				$table->integer('manager_score')->default('0');
				$table->integer('company_score')->default('0');
				$table->integer('created_by');
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
		