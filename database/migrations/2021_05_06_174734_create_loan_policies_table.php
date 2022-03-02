<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPoliciesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('loan_policies', function(Blueprint $table){
			$table->increments('id');	
				$table->decimal('annual_interest', 10, 2)->nullable();
				$table->decimal('maximum_allowed', 10, 2)->nullable();
				$table->integer('user_id')->nullable()->default('0');
				$table->integer('company_id')->default('0');
				$table->integer('workflow_id')->default('0');
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
		