<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBscWeightsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('bsc_weights', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('department_id');
				$table->integer('performance_category_id');
				$table->integer('metric_id');
				$table->decimal('percentage', 10, 2)->nullable();
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
		