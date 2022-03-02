<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBehavioralSubmetricsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('behavioral_submetrics', function(Blueprint $table){
			$table->increments('id');	
				$table->string('objective');
				$table->text('measure');
				$table->decimal('weighting', 10, 2);
				$table->decimal('low_target', 10, 2);
				$table->decimal('mid_target', 10, 2);
				$table->decimal('upper_target', 10, 2);
				$table->integer('status');
				$table->integer('company_id');
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
		