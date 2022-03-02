<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSectionsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('user_sections', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->integer('company_id');
				$table->text('other_name')->nullable();
				$table->string('salary_project_code')->nullable();
				$table->string('charge_project_code')->nullable();
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
		