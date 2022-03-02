<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('documents', function(Blueprint $table){
			$table->increments('id');	
				$table->string('document');
				$table->string('type_id');
				$table->integer('user_id');
				$table->integer('last_mod_id')->nullable();
				$table->string('expiry')->nullable();
				$table->integer('company_id')->nullable();
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
		