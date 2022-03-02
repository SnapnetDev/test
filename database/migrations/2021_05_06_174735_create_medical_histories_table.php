<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalHistoriesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('medical_histories', function(Blueprint $table){
			$table->increments('id');	
				$table->string('user_id');
				$table->text('current_medical_conditions')->nullable();
				$table->text('past_medical_conditions')->nullable();
				$table->text('surgeries_hospitalizations')->nullable();
				$table->text('medications')->nullable();
				$table->text('medication_allergies')->nullable();
				$table->text('family_history')->nullable();
				$table->text('social_history')->nullable();
				$table->text('others')->nullable();
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
		