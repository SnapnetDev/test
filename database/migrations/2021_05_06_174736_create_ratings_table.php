<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('ratings', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('emp_id');
				$table->integer('goal_id')->nullable();
				$table->integer('lm_rate')->nullable();
				$table->integer('lm_id')->nullable();
				$table->text('lm_comment')->nullable();
				$table->integer('admin_id')->nullable();
				$table->integer('admin_rate')->nullable();
				$table->text('admin_comment')->nullable();
				$table->integer('quarter')->nullable();
				$table->text('emp_comment')->nullable();
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
		