<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('trainings', function(Blueprint $table){
			$table->increments('id');	
				$table->string('training_name');
				$table->string('training_mode');
				$table->string('duration');
				$table->integer('department_id');
				$table->text('remark')->nullable();
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
		