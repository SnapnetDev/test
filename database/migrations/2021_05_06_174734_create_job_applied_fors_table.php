<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobAppliedForsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('job_applied_fors', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->integer('job_id');
				$table->integer('status');
				$table->integer('resume_id');
				$table->integer('complete')->default('0');
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
		