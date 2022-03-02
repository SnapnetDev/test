<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanRequestsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('loan_requests', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->decimal('netpay', 10, 2)->nullable();
				$table->decimal('amount', 10, 2)->nullable();
				$table->decimal('monthly_deduction', 10, 2)->nullable();
				$table->integer('period')->nullable();
				$table->integer('months_deducted')->default('0');
				$table->decimal('current_rate', 10, 2)->nullable();
				$table->string('repayment_starts')->nullable();
				$table->integer('status')->nullable();
				$table->integer('workflow_id')->nullable();
				$table->integer('company_id')->default('0');
				$table->integer('completed')->nullable();
				$table->integer('loan_type_id')->nullable();
				$table->integer('approved_by')->default('0');
				$table->integer('guarantor_id')->nullable();
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
		