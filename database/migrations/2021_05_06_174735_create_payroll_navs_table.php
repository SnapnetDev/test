<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollNavsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('payroll_navs', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('payroll_id');
				$table->string('code');
				$table->string('date');
				$table->string('gl_code')->nullable();
				$table->text('description')->nullable();
				$table->string('project_code')->nullable();
				$table->decimal('dr', 10, 2);
				$table->decimal('cr', 10, 2);
				$table->integer('read')->default('0');
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
		