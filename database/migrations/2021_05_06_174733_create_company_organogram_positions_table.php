<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyOrganogramPositionsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('company_organogram_positions', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->integer('company_id')->default('0');
				$table->integer('user_id')->default('0');
				$table->integer('p_id')->default('0');
				$table->integer('sp_id')->default('0');
				$table->integer('company_organogram_id')->default('0');
				$table->integer('company_organogram_level_id')->default('0');
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
		