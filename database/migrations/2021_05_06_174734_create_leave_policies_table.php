<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavePoliciesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('leave_policies', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('includes_weekend')->default('0');
				$table->integer('includes_holiday')->default('0');
				$table->integer('workflow_id')->default('0');
				$table->integer('company_id')->default('0');
				$table->integer('user_id')->default('0');
				$table->integer('default_length')->nullable();
				$table->integer('uses_spillover')->nullable()->default('0');
				$table->integer('uses_maximum_spillover')->default('0');
				$table->integer('spillover_length')->default('0');
				$table->integer('spillover_month')->default('12');
				$table->integer('spillover_day')->default('31');
				$table->integer('relieve_approves')->default('0');
				$table->integer('probationer_applies')->default('0');
				$table->integer('uses_casual_leave')->default('0');
				$table->integer('casual_leave_length')->default('0');
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
		