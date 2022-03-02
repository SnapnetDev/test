<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBscEvaluationDetailsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('bsc_evaluation_details', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('metric_id');
				$table->text('measure');
				$table->string('business_goal')->nullable();
				$table->string('source')->nullable();
				$table->decimal('lower', 10, 2)->nullable();
				$table->decimal('mid', 10, 2)->nullable();
				$table->decimal('upper', 10, 2)->nullable();
				$table->decimal('actual', 10, 2)->nullable();
				$table->decimal('weighting', 10, 2)->nullable();
				$table->integer('bsc_evaluation_id');
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
		