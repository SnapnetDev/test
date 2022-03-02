<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmfObjectivesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('pmf_objectives', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->string('date_of_appraiser');
				$table->text('perf_objective');
				$table->text('specific');
				$table->text('measureable');
				$table->text('achievable');
				$table->text('relevant');
				$table->text('time_bound');
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
		