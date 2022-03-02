<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmfPersonalEvaluationTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('pmf_personal_evaluation', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->text('description');
				$table->string('weight');
				$table->string('score');
				$table->string('from')->nullable();
				$table->string('to')->nullable();
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
		