<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQueryThreadsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('query_threads', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('parent_id')->default('0');
				$table->integer('created_by');
				$table->integer('queried_user_id');
				$table->string('status')->default('open');
				$table->string('query_type_id');
				$table->text('content');
				$table->integer('num_of_reminders')->default('0');
				$table->string('action_taken')->default('NA');
				$table->string('effective_date')->nullable();
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
		