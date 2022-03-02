<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrTrainingBudgetTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('tr_training_budget', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('hr_id')->nullable();
				$table->string('training_budget_name')->nullable();
				$table->string('allocation_total')->nullable();
				$table->string('year_of_allocation')->nullable();
				$table->integer('status')->nullable();
				$table->integer('dep_id')->nullable();
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
		