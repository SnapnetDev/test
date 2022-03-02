<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJoblistingsExternalTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('joblistings_external', function(Blueprint $table){
			$table->increments('id');	
				$table->string('job_ref')->nullable();
				$table->string('title')->nullable();
				$table->integer('level');
				$table->integer('location_id')->default('0');
				$table->integer('experience_from')->default('0');
				$table->integer('experience_to')->default('0');
				$table->text('description')->nullable();
				$table->text('experience')->nullable();
				$table->text('skills')->nullable();
				$table->integer('country_id')->nullable();
				$table->integer('state_id')->nullable();
				$table->string('expires')->nullable();
				$table->integer('status')->default('0');
				$table->integer('salary_from');
				$table->integer('salary_to');
				$table->integer('user_id');
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
		