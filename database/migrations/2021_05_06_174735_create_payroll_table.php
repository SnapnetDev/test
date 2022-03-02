<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('payroll', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('month')->nullable();
				$table->integer('year')->nullable();
				$table->string('for')->nullable();
				$table->integer('payslip_issued')->default('0');
				$table->integer('workflow_id')->nullable();
				$table->integer('company_id')->nullable();
				$table->integer('approved')->default('0');
				$table->integer('disbursed')->default('0');
				$table->integer('section_id')->default('0');
				$table->decimal('netpay', 10, 2)->nullable();
				$table->integer('user_id')->nullable()->default('0');
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
		