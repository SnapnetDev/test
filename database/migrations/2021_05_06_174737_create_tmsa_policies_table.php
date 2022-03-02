<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmsaPoliciesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('tmsa_policies', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('company_id');
				$table->decimal('onshore_day_rate', 10, 2)->nullable();
				$table->decimal('brt_percentage', 10, 2)->nullable();
				$table->decimal('out_of_station', 10, 2)->nullable();
				$table->integer('workflow_id')->default('0');
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
		