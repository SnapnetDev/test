<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailManagementTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('mail_management', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('userId');
				$table->string('sender');
				$table->string('receiver');
				$table->string('subject');
				$table->string('email');
				$table->string('phone');
				$table->string('direction');
				$table->string('status');
				$table->string('comments');
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
		