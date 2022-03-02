<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuspensionsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('suspensions', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->integer('suspension_type_id');
				$table->string('start_date')->nullable();
				$table->string('end_date')->nullable();
				$table->integer('length')->nullable();
				$table->text('comment')->nullable();
				$table->integer('created_by');
				$table->integer('company_id');
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
		