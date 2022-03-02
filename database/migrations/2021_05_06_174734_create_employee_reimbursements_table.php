<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeReimbursementsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('employee_reimbursements', function(Blueprint $table){
			$table->increments('id');	
				$table->string('title');
				$table->integer('expense_reimbursement_type_id');
				$table->string('expense_date')->nullable();
				$table->decimal('amount', 10, 2)->nullable();
				$table->string('disbursement_date')->nullable();
				$table->integer('disbursed')->default('0');
				$table->string('attachment')->nullable();
				$table->integer('user_id');
				$table->integer('workflow_id');
				$table->integer('status')->default('0');
				$table->integer('company_id');
				$table->text('description')->nullable();
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
		