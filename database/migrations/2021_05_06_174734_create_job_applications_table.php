<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobApplicationsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('job_applications', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('applicable_id');
				$table->string('applicable_type');
				$table->string('resume')->nullable();
				$table->text('cover_letter')->nullable();
				$table->integer('job_listing_id');
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
		