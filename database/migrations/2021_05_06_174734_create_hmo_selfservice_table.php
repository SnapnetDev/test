<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHmoSelfserviceTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('hmo_selfservice', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('userId');
				$table->integer('hmo')->nullable();
				$table->string('health_plan_type')->nullable();
				$table->string('precondition')->nullable();
				$table->string('genotype')->nullable();
				$table->string('bloodgroup')->nullable();
				$table->string('primary_hospital')->nullable();
				$table->string('secondary_hospital')->nullable();
				$table->integer('status')->default('0');
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
		