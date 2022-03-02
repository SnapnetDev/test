<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExemptionsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('exemptions', function(Blueprint $table){
			$table->increments('id');	
				$table->string('type');
				$table->string('user_id');
				$table->string('attendance_id');
				$table->text('reason')->nullable();
				$table->string('approved')->default('no');
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
		