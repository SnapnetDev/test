<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidaysTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('holidays', function(Blueprint $table){
			$table->increments('id');	
				$table->string('date')->nullable();
				$table->string('title');
				$table->string('status')->default('1');
				$table->integer('company_id')->nullable();
				$table->integer('created_by');
				$table->string('message');
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
		