<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBehaviourEvaluationDetailsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('behaviour_evaluation_details', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('bsc_evaluation_id');
				$table->integer('behavioral_sub_metric_id');
				$table->decimal('actual', 10, 2)->nullable();
				$table->text('comment')->nullable();
				$table->decimal('crra', 10, 2)->nullable();
				$table->decimal('wcp', 10, 2)->nullable();
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
		