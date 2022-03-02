<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveRequestRecallsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('leave_request_recalls', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('leave_request_id')->default('0');
				$table->integer('recaller_id')->default('0');
				$table->string('old_date')->nullable();
				$table->string('new_date')->nullable();
				$table->text('recall_reason')->nullable();
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
		