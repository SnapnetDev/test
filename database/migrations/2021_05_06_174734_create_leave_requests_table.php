<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveRequestsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('leave_requests', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('leave_id')->default('0');
				$table->integer('user_id')->default('0');
				$table->integer('replacement_id')->default('0');
				$table->integer('balance')->default('0');
				$table->string('start_date')->nullable();
				$table->string('end_date')->nullable();
				$table->text('reason')->nullable();
				$table->integer('workflow_id')->default('0');
				$table->integer('status')->default('0');
				$table->integer('length')->default('0');
				$table->integer('paystatus')->default('0');
				$table->text('absence_doc')->nullable();
				$table->integer('leave_bank')->default('0');
				$table->integer('company_id')->nullable();
				$table->integer('relieve_approved')->default('0');
				$table->string('relieve_comment')->nullable();
				$table->string('relieve_approved_at')->nullable();
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
		