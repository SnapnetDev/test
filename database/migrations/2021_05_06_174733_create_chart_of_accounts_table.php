<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartOfAccountsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('chart_of_accounts', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->string('code')->nullable();
				$table->text('description')->nullable();
				$table->integer('type')->default('0');
				$table->integer('display')->default('0');
				$table->integer('position')->nullable();
				$table->string('salary_component_constant')->nullable();
				$table->integer('specific_salary_component_type_id')->nullable();
				$table->integer('nationality_display')->nullable();
				$table->string('other_constant')->nullable();
				$table->integer('source');
				$table->string('formula')->nullable();
				$table->string('salary_charge')->nullable();
				$table->integer('non_payroll_provision')->default('0');
				$table->integer('status')->default('0');
				$table->integer('company_id');
				$table->integer('broadsheet_status')->default('0');
				$table->integer('uses_group')->default('0');
				$table->integer('group_id')->default('0');
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
		