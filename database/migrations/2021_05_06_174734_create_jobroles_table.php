<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobrolesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('jobroles', function(Blueprint $table){
			$table->increments('id');	
				$table->string('title')->nullable();
				$table->text('description')->nullable();
				$table->integer('department_id')->default('0');
				$table->integer('qualification_id')->nullable();
				$table->integer('parent_id')->nullable();
				$table->integer('personnel')->nullable();
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
		