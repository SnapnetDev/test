<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBscDetDetailsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('bsc_det_details', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('bsc_det_id');
				$table->integer('bsc_metric_id');
				$table->string('business_goal')->nullable();
				$table->string('measure')->nullable();
				$table->decimal('lower', 10, 2)->nullable();
				$table->decimal('mid', 10, 2)->nullable();
				$table->decimal('upper', 10, 2)->nullable();
				$table->decimal('weighting', 10, 2)->nullable();
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
		