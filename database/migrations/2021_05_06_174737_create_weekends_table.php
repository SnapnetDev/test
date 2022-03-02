<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeekendsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('weekends', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('company_id');
				$table->integer('monday')->default('0');
				$table->integer('tuesday')->default('0');
				$table->integer('wednesday')->default('0');
				$table->integer('thursday')->default('0');
				$table->integer('friday')->default('0');
				$table->integer('saturday')->default('0');
				$table->integer('sunday')->default('0');
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
		