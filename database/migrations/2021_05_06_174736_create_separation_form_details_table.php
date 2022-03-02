<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeparationFormDetailsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('separation_form_details', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('separation_question_id');
				$table->integer('separation_form_id');
				$table->text('answer')->nullable();
				$table->integer('separation_question_option_id')->default('0');
				$table->string('type');
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
		