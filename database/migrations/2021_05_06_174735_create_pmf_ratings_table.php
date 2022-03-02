<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmfRatingsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('pmf_ratings', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->text('description');
				$table->integer('weight');
				$table->integer('score');
				$table->string('from')->nullable();
				$table->string('to')->nullable();
				$table->integer('created_by');
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
		