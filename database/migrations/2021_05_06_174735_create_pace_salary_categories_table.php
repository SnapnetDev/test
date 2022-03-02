<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaceSalaryCategoriesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('pace_salary_categories', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->integer('unionized')->default('0');
				$table->integer('uses_timesheet')->default('0');
				$table->integer('uses_tax')->default('1');
				$table->decimal('basic_salary', 10, 2)->nullable();
				$table->decimal('relief', 10, 2)->nullable();
				$table->integer('uses_daily_net')->default('0');
				$table->integer('company_id');
				$table->decimal('tax_rate', 10, 2)->default('1.00');
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
		