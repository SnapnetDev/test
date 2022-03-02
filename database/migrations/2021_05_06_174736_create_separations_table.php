<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeparationsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('separations', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->integer('separation_type_id');
				$table->string('date_of_separation');
				$table->string('hiredate')->nullable();
				$table->text('comment')->nullable();
				$table->integer('days_of_employment')->nullable();
				$table->integer('company_id')->default('0');
				$table->string('exit_checkout_form')->nullable();
				$table->string('exit_interview_form')->nullable();
				$table->integer('stage')->default('0');
				$table->integer('workflow_id')->default('0');
				$table->integer('approved')->default('0');
				$table->integer('initiator_id')->default('0');
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
		