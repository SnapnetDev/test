<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpHistoriesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('emp_histories', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->string('organization');
				$table->string('position');
				$table->string('start_date');
				$table->string('end_date');
				$table->integer('last_change_approved')->default('1');
				$table->integer('last_change_approved_by')->default('0');
				$table->string('last_change_approved_on')->nullable();
				$table->integer('company_id')->default('0');
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
		