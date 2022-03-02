<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('branches', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name')->nullable();
				$table->string('address')->nullable();
				$table->string('email')->nullable();
				$table->integer('company_id')->nullable()->default('0');
				$table->integer('manager_id')->nullable()->default('0');
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
		