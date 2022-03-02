<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateE360PeerEvaluationNomineesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('e360_peer_evaluation_nominees', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('mp_id');
				$table->integer('nominee_id');
				$table->integer('user_id');
				$table->integer('status')->default('0');
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
		