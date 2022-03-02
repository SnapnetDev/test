<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJoblistingsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('joblistings', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('job_id');
				$table->integer('type')->default('1');
				$table->decimal('salary_from', 10, 2)->nullable();
				$table->decimal('salary_to', 10, 2)->nullable();
				$table->string('expires')->nullable();
				$table->integer('status')->default('0');
				$table->integer('level');
				$table->integer('employee_class_id');
				$table->integer('experience_from')->nullable();
				$table->integer('experience_to')->nullable();
				$table->text('requirements')->nullable();
				$table->integer('company_id');
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
		