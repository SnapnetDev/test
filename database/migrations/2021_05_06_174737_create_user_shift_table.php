<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserShiftTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('user_shift', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user-id');
				$table->integer('shift_id');
				$table->string('start_date')->nullable();
				$table->string('end_date')->nullable();
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
		