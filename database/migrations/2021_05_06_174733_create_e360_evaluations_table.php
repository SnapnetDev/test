<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateE360EvaluationsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('e360_evaluations', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->integer('mp_id');
				$table->integer('evaluator_id');
				$table->string('evaluated_at')->nullable();
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
		