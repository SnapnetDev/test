<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('companies', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->string('email');
				$table->text('address');
				$table->integer('user_id')->nullable();
				$table->string('logo')->nullable();
				$table->integer('is_parent')->default('0');
				$table->string('color')->nullable();
				$table->string('biometric_serial')->nullable();
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
		