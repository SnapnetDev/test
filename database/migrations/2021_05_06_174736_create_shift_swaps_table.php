<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftSwapsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('shift_swaps', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('owner_id')->nullable();
				$table->integer('swapper_id')->nullable();
				$table->integer('status')->nullable();
				$table->integer('approved_by')->nullable();
				$table->integer('user_shift_schedule_id')->nullable();
				$table->integer('new_shift_id')->nullable();
				$table->string('date')->nullable();
				$table->text('reason')->nullable();
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
		