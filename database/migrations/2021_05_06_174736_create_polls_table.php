<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('polls', function(Blueprint $table){
			$table->increments('id');	
				$table->string('user_id');
				$table->string('name');
				$table->string('description');
				$table->string('end_date');
				$table->string('status')->default('pending');
				$table->string('type');
				$table->text('roles')->nullable();
				$table->text('groups')->nullable();
				$table->text('departments')->nullable();
				$table->text('results')->nullable();
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
		