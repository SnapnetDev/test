<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingBudgetTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('training_budget', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('trainee_id');
				$table->string('purpose');
				$table->string('amount');
				$table->integer('status_id')->nullable();
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
		