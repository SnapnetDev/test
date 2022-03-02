<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHmoSelfserviceDependentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('hmo_selfservice_dependents', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('userId');
				$table->string('type')->nullable();
				$table->string('fullname')->nullable();
				$table->string('date_of_birth')->nullable();
				$table->string('gender')->nullable();
				$table->string('primary_hospital')->nullable();
				$table->string('secondary_hospital')->nullable();
				$table->string('health_plan_type')->nullable();
				$table->string('occupation')->nullable();
				$table->string('pre_condition')->nullable();
				$table->string('phone')->nullable();
				$table->string('email')->nullable();
				$table->text('passport')->nullable();
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
		