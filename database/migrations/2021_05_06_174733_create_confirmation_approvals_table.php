<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfirmationApprovalsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('confirmation_approvals', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('confirmation_id');
				$table->integer('stage_id');
				$table->integer('approver_id');
				$table->text('comments');
				$table->integer('status');
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
		