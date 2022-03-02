<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeparationQuestionsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('separation_questions', function(Blueprint $table){
			$table->increments('id');	
				$table->text('question');
				$table->string('type')->default('txt');
				$table->integer('separation_question_category_id')->default('0');
				$table->integer('status')->default('0');
				$table->integer('compulsory')->default('0');
				$table->integer('company_id')->default('0');
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
		