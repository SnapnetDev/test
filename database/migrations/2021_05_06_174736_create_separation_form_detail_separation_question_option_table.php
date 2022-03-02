<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeparationFormDetailSeparationQuestionOptionTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('separation_form_detail_separation_question_option', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('separation_form_detail_id');
				$table->integer('separation_question_option_id');
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
		