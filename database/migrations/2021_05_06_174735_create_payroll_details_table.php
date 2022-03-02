<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollDetailsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('payroll_details', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('payroll_id');
				$table->integer('user_id');
				$table->decimal('annual_gross_pay', 10, 2);
				$table->decimal('gross_pay', 10, 2);
				$table->decimal('basic_pay', 10, 2);
				$table->decimal('deductions', 10, 2)->nullable();
				$table->decimal('allowances', 10, 2)->nullable();
				$table->integer('working_days')->nullable();
				$table->integer('worked_days')->nullable();
				$table->string('details')->nullable();
				$table->decimal('sc_allowances', 10, 2)->nullable();
				$table->decimal('sc_deductions', 10, 2)->nullable();
				$table->decimal('ssc_allowances', 10, 2)->nullable();
				$table->decimal('ssc_deductions', 10, 2)->nullable();
				$table->string('sc_details')->nullable();
				$table->string('ssc_details')->nullable();
				$table->integer('is_anniversary')->nullable();
				$table->decimal('taxable_income', 10, 2)->nullable();
				$table->decimal('annual_paye', 10, 2)->nullable();
				$table->decimal('paye', 10, 2)->nullable();
				$table->decimal('consolidated_allowance', 10, 2)->nullable();
				$table->decimal('netpay', 10, 2)->nullable();
				$table->string('payroll_type')->nullable();
				$table->decimal('union_dues', 10, 2)->nullable();
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
		