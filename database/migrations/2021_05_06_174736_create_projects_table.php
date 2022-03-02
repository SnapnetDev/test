<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('projects', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name')->nullable();
				$table->string('code')->nullable();
				$table->string('start_date')->nullable();
				$table->string('end_est_date')->nullable();
				$table->string('actual_ending_date')->nullable();
				$table->integer('project_task_id')->nullable();
				$table->string('remark')->nullable();
				$table->integer('project_manager_id')->default('0');
				$table->integer('client_id')->nullable();
				$table->integer('lm_id')->nullable();
				$table->integer('company_id')->nullable();
				$table->integer('status')->default('0');
				$table->string('progress')->default('on-going');
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
		