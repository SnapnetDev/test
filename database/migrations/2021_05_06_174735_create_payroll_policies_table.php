<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollPoliciesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('payroll_policies', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('payroll_runs')->nullable();
				$table->decimal('basic_pay_percentage', 10, 2)->nullable();
				$table->integer('user_id')->nullable();
				$table->integer('workflow_id')->nullable();
				$table->integer('use_office')->default('0');
				$table->integer('use_tmsa')->default('0');
				$table->integer('use_project')->default('0');
				$table->integer('use_lateness')->default('0');
				$table->integer('show_all_gross')->default('1');
				$table->integer('display_lsa_on_nav_export')->default('0');
				$table->integer('display_lsa_on_payroll_export')->default('0');
				$table->integer('company_id')->nullable();
				$table->integer('uses_approval')->default('0');
				$table->integer('suspension_prorates')->default('1');
				$table->integer('new_hire_prorates')->default('1');
				$table->integer('separation_prorates')->default('1');
				$table->integer('leave_spill_is_paid')->default('1');
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
		