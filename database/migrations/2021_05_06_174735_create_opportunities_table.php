<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpportunitiesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('opportunities', function(Blueprint $table){
			$table->increments('id');	
				$table->string('client_id');
				$table->string('project_name');
				$table->string('date');
				$table->string('project_status')->default('pending');
				$table->string('payment_status')->default('pending');
				$table->string('project_amount')->default('0');
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
		