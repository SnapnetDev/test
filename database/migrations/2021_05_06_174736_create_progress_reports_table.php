<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressReportsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('progress_reports', function(Blueprint $table){
			$table->increments('id');	
				$table->text('report')->nullable();
				$table->string('from')->nullable();
				$table->string('to')->nullable();
				$table->string('achievedamount')->nullable();
				$table->string('achievedtodate')->nullable();
				$table->text('reportcomment')->nullable();
				$table->integer('status')->nullable();
				$table->integer('emp_id')->nullable();
				$table->integer('kpiid')->nullable();
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
		