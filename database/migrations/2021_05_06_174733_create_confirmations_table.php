<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfirmationsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('confirmations', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->integer('initiator_id');
				$table->integer('status')->default('0');
				$table->string('confirmation_date')->nullable();
				$table->integer('workflow_id');
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
		