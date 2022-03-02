<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveAllowancePaymentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('leave_allowance_payments', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->integer('year');
				$table->string('amount')->nullable();
				$table->string('tax_rate')->nullable();
				$table->string('tax_amount')->default('1');
				$table->integer('disbursed');
				$table->integer('approved');
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
		