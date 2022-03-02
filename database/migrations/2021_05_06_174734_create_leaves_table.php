<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('leaves', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->integer('length')->default('0');
				$table->integer('with_pay')->default('0');
				$table->integer('company_id')->nullable()->default('0');
				$table->integer('created_by')->default('0');
				$table->string('marital_status')->nullable();
				$table->string('gender')->nullable();
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
		