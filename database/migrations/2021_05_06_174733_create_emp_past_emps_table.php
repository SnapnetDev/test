<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpPastEmpsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('emp_past_emps', function(Blueprint $table){
			$table->increments('id');	
				$table->string('organization')->nullable();
				$table->string('role')->nullable();
				$table->integer('emp_id')->nullable();
				$table->string('from')->nullable();
				$table->string('to')->nullable();
				$table->text('job_desc')->nullable();
				$table->integer('job_level')->nullable();
				$table->string('address')->nullable();
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
		