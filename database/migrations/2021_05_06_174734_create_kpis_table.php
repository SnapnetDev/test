<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpisTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('kpis', function(Blueprint $table){
			$table->increments('id');	
				$table->string('deliverable');
				$table->integer('targetweight');
				$table->integer('targetamount');
				$table->integer('status');
				$table->text('comment');
				$table->integer('department_id')->default('0');
				$table->integer('created_by')->default('0');
				$table->integer('approved')->default('0');
				$table->integer('approval_id')->default('0');
				$table->text('reason')->nullable();
				$table->integer('assigned_to')->nullable()->default('0');
				$table->integer('quarter')->nullable()->default('0');
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
		