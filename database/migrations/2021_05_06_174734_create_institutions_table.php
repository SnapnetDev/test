<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('institutions', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->string('name');
				$table->string('course');
				$table->string('degree');
				$table->string('degree_class')->nullable();
				$table->integer('start_year');
				$table->integer('end_year')->nullable();
				$table->integer('country_id')->nullable();
				$table->text('description')->nullable();
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
		