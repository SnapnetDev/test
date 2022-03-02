<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProbationPoliciesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('probation_policies', function(Blueprint $table){
			$table->increments('id');	
				$table->string('probation_period');
				$table->string('probation_reminder');
				$table->string('automatic_probation');
				$table->string('notify_roles');
				$table->integer('created_by');
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
		