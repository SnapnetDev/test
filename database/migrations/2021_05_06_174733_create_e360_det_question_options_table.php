<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateE360DetQuestionOptionsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('e360_det_question_options', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('e360_det_question_id');
				$table->string('content');
				$table->integer('score')->default('0');
				$table->integer('company_id')->default('0');
				$table->integer('user_id')->default('0');
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
		