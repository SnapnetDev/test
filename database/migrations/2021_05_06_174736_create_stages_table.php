<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('stages', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->integer('workflow_id');
				$table->integer('type')->default('0');
				$table->integer('user_id')->nullable()->default('0');
				$table->integer('role_id')->nullable()->default('0');
				$table->integer('group_id')->nullable()->default('0');
				$table->integer('position');
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
		