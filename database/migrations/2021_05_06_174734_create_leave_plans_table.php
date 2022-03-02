<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavePlansTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('leave_plans', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->string('start_date')->nullable();
				$table->string('end_date')->nullable();
				$table->integer('length')->nullable();
				$table->integer('company_id');
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
		