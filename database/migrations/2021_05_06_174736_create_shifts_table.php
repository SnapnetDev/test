<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('shifts', function(Blueprint $table){
			$table->increments('id');	
				$table->string('type');
				$table->string('start_time')->nullable();
				$table->string('end_time')->nullable();
				$table->integer('company_id')->default('0');
				$table->string('color_code')->nullable();
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
		