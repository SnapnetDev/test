<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeparationApprovalListsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('separation_approval_lists', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->integer('created_by');
				$table->integer('company_id');
				$table->integer('save_profile');
				$table->integer('save_');
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
		