<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnboardingEmployeeChecklistsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('onboarding_employee_checklists', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('employee_id')->nullable();
				$table->integer('onboarding_check_list_id')->nullable();
				$table->text('comments')->nullable();
				$table->string('support_document')->nullable();
				$table->integer('status')->nullable();
				$table->integer('confirmed_by')->nullable();
				$table->text('comment_employees')->nullable();
				$table->text('comment_supervisor')->nullable();
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
		