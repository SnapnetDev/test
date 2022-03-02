<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftSwapApprovalsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('shift_swap_approvals', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('shift_swap_id')->default('0');
				$table->integer('stage_id')->default('0');
				$table->integer('approver_id')->default('0');
				$table->text('comments')->nullable();
				$table->integer('status')->default('0');
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
		