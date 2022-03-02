<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrUserOfflineTrainingsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('tr_user_offline_trainings', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('training_plan_id')->nullable();
				$table->integer('user_id')->nullable();
				$table->integer('completed')->nullable();
				$table->integer('status')->nullable();
				$table->string('reason')->nullable();
				$table->string('progress_notes')->nullable();
				$table->string('feedback')->nullable();
				$table->string('rating')->nullable();
				$table->string('upload1')->nullable();
				$table->string('upload2')->nullable();
				$table->string('upload3')->nullable();
				$table->string('progress')->nullable();
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
		