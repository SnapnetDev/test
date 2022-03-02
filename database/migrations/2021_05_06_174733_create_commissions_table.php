<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('commissions', function(Blueprint $table){
			$table->increments('id');	
				$table->string('opportunity_id');
				$table->string('staff_id');
				$table->string('expected_commission')->nullable();
				$table->string('commission');
				$table->string('payment_status');
				$table->string('payment_date')->nullable();
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
		