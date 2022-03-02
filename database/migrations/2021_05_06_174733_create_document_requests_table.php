<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentRequestsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('document_requests', function(Blueprint $table){
			$table->increments('id');	
				$table->string('title');
				$table->integer('document_request_type_id');
				$table->string('due_date')->nullable();
				$table->string('file')->nullable();
				$table->integer('user_id');
				$table->integer('workflow_id');
				$table->integer('status')->default('0');
				$table->integer('company_id');
				$table->text('comment')->nullable();
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
		