<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiUserScoreTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('kpi_user_score', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id')->nullable();
				$table->integer('kpi_data_id')->nullable();
				$table->string('manager_score')->nullable();
				$table->text('manager_comment')->nullable();
				$table->string('user_score')->nullable();
				$table->text('user_comment')->nullable();
				$table->string('hr_score')->nullable();
				$table->text('hr_comment')->nullable();
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
		