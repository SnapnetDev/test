<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpPromotionHistoriesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('emp_promotion_histories', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->integer('approved_by');
				$table->string('approved_on');
				$table->integer('old_grade_id');
				$table->integer('grade_id');
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
		