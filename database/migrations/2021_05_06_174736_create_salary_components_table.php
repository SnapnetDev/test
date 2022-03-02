<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryComponentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('salary_components', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->string('constant');
				$table->string('type');
				$table->string('formula');
				$table->string('gl_code')->nullable();
				$table->string('project_code')->nullable();
				$table->string('comment')->nullable();
				$table->string('status')->default('0');
				$table->integer('company_id')->nullable();
				$table->integer('taxable')->default('0');
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
		