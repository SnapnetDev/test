<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrOfflineTrainingsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('tr_offline_trainings', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name')->nullable();
				$table->string('cost_per_head')->nullable();
				$table->integer('number_of_enrollees')->nullable();
				$table->string('grand_total')->nullable();
				$table->integer('line_manager_id')->nullable();
				$table->integer('status')->nullable();
				$table->string('reason')->nullable();
				$table->integer('approved_by')->nullable();
				$table->integer('last_modified_by')->nullable();
				$table->string('train_start')->nullable();
				$table->string('train_stop')->nullable();
				$table->string('year_of_training')->nullable();
				$table->integer('role_id')->nullable();
				$table->integer('dep_id')->nullable();
				$table->string('type')->default('offline');
				$table->integer('remote_id')->nullable();
				$table->string('resource_url')->nullable();
				$table->string('enroll_instructions')->nullable();
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
		