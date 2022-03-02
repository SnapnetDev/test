<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpAcademicsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('emp_academics', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('qualification_id')->nullable();
				$table->string('title');
				$table->string('year');
				$table->string('institution');
				$table->string('grade');
				$table->string('course');
				$table->integer('emp_id');
				$table->integer('last_change_approved')->default('1');
				$table->integer('last_change_approved_by')->default('0');
				$table->string('last_change_approved_on')->nullable();
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
		