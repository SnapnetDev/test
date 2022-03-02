<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateE360DetQuestionsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('e360_det_questions', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('mp_id');
				$table->integer('question_category_id')->default('0');
				$table->text('question');
				$table->text('self_question')->nullable();
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
		