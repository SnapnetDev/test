<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyDocumentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('company_documents', function(Blueprint $table){
			$table->increments('id');	
				$table->string('title');
				$table->text('description')->nullable();
				$table->string('file')->nullable();
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
		