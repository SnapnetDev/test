<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNPUsersTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('n_p_users', function(Blueprint $table){
			$table->increments('id');	
				$table->string('user_id');
				$table->string('n_p_measurement_period_id');
				$table->string('score');
				$table->string('user_response')->nullable();
				$table->text('user_comment')->nullable();
				$table->string('manager_id')->nullable();
				$table->string('manager_response')->nullable();
				$table->text('manager_comment')->nullable();
				$table->string('sos_id')->nullable();
				$table->string('sos_response')->nullable();
				$table->text('sos_comment')->nullable();
				$table->string('line_executive_id')->nullable();
				$table->string('line_executive_response')->nullable();
				$table->text('line_executive_comment')->nullable();
				$table->string('status')->default('pending');
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
		