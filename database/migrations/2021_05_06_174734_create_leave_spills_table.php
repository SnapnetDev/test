<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveSpillsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('leave_spills', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->integer('from_year');
				$table->integer('to_year');
				$table->integer('days')->nullable();
				$table->integer('used')->nullable()->default('0');
				$table->integer('valid')->default('0');
				$table->integer('actual_days')->default('0');
				$table->integer('modified_by')->default('0');
				$table->string('modification_reason')->nullable();
				$table->integer('paid')->default('0');
				$table->integer('company_id')->default('0');
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
		