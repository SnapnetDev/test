<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyOrganogramsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('company_organograms', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('company_id')->default('0');
				$table->integer('manager_id')->default('0');
				$table->string('name');
				$table->integer('updated_by')->default('0');
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
		