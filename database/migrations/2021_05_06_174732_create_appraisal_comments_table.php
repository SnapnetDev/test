<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppraisalCommentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('appraisal_comments', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->text('description');
				$table->integer('status');
				$table->integer('manager_employee_use');
				$table->integer('created_by');
				$table->integer('updated_by');
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
		