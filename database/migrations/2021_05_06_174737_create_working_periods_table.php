<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkingPeriodsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('working_periods', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('company_id');
				$table->string('sob')->nullable();
				$table->string('cob')->nullable();
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
		