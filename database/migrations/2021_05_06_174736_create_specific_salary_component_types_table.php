<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecificSalaryComponentTypesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('specific_salary_component_types', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->integer('type')->default('0');
				$table->integer('company_id');
				$table->integer('display')->default('1');
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
		