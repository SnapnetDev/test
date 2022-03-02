<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmsaPayrollDetailsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('tmsa_payroll_details', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('payroll_id')->nullable()->default('0');
				$table->integer('user_id');
				$table->decimal('onshore_day_rate', 10, 2)->nullable();
				$table->integer('days_worked_onshore')->default('0');
				$table->decimal('offshore_day_rate', 10, 2)->nullable();
				$table->integer('days_worked_offshore')->default('0');
				$table->decimal('total_gross_pay', 10, 2)->nullable();
				$table->decimal('annual_gross_pay', 10, 2)->nullable();
				$table->decimal('annual_employee_pension_contribution', 10, 2)->nullable();
				$table->decimal('monthly_employee_pension_contribution', 10, 2)->nullable();
				$table->decimal('allowances', 10, 2)->nullable();
				$table->decimal('deductions', 10, 2)->nullable();
				$table->decimal('personal_allowances', 10, 2)->nullable();
				$table->decimal('personal_deductions', 10, 2)->nullable();
				$table->text('details')->nullable();
				$table->text('personal_details')->nullable();
				$table->decimal('cra', 10, 2)->nullable();
				$table->decimal('total_relief', 10, 2)->nullable();
				$table->decimal('taxable_income', 10, 2)->nullable();
				$table->decimal('annual_paye', 10, 2)->nullable();
				$table->decimal('monthly_paye', 10, 2)->nullable();
				$table->decimal('netpay', 10, 2)->nullable();
				$table->decimal('out_of_station_allowance', 10, 2)->nullable();
				$table->decimal('brt_allowance', 10, 2)->nullable();
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
		