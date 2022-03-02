<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNokTempsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('nok_temps', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->string('name');
				$table->string('relationship')->nullable();
				$table->string('phone')->nullable();
				$table->text('address')->nullable();
				$table->string('last_change_approved_on')->nullable();
				$table->integer('last_change_approved')->default('1');
				$table->integer('last_change_approved_by')->default('0');
				$table->integer('company_id')->default('0');
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
		