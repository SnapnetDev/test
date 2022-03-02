<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('goals', function(Blueprint $table){
			$table->increments('id');	
				$table->string('objective');
				$table->string('commitment');
				$table->integer('user_id');
				$table->integer('assigned_to');
				$table->integer('goal_cat_id');
				$table->integer('quarter')->nullable()->default('0');
				$table->integer('company_id')->default('1');
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
		