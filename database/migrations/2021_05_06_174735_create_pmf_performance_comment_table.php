<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmfPerformanceCommentTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('pmf_performance_comment', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->text('overall_perf');
				$table->text('line_manager_comment');
				$table->text('holder_comment');
				$table->string('employee_sign')->nullable();
				$table->string('appraiser_sign')->nullable();
				$table->string('from')->nullable();
				$table->string('to')->nullable();
				$table->integer('created_by');
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
		