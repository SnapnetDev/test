<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingRecommendedTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('training_recommended', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('training_id');
				$table->integer('budget_id')->nullable();
				$table->string('training_mode');
				$table->string('duration');
				$table->string('proposed_start_date')->nullable();
				$table->string('proposed_end_date')->nullable();
				$table->integer('status_id')->nullable()->default('0');
				$table->integer('approval_id')->nullable()->default('0');
				$table->text('remark')->nullable();
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
		