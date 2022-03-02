<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartOfAccountComponentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('chart_of_account_components', function(Blueprint $table){
			$table->increments('id');	
				$table->string('salary_component_constant')->nullable();
				$table->integer('specific_salary_component_type_id')->default('0');
				$table->string('payroll_constant')->nullable();
				$table->integer('chart_of_account_id');
				$table->integer('source')->default('0');
				$table->string('operator');
				$table->decimal('amount', 10, 2)->default('0.00');
				$table->decimal('percentage', 10, 2)->default('0.00');
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
		