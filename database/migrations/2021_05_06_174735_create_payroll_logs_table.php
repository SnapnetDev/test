<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollLogsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('payroll_logs', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('payroll_id')->default('0');
				$table->string('for')->nullable();
				$table->text('details')->nullable();
				$table->integer('user_id');
				$table->integer('created_by');
				$table->string('payroll_type')->nullable();
				$table->integer('status')->default('0');
				$table->text('issue')->nullable();
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
		