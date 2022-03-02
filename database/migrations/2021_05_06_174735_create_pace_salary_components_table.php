<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaceSalaryComponentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('pace_salary_components', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name')->nullable();
				$table->integer('pace_salary_category_id');
				$table->integer('type');
				$table->integer('fixed')->default('0');
				$table->integer('uses_days')->default('0');
				$table->integer('uses_anniversary')->nullable()->default('0');
				$table->string('constant')->nullable();
				$table->string('formula')->nullable();
				$table->decimal('amount', 10, 2)->nullable();
				$table->integer('status')->default('0');
				$table->integer('taxable')->default('0');
				$table->integer('company_id');
				$table->string('gl_code')->nullable();
				$table->string('project_code')->nullable();
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
		