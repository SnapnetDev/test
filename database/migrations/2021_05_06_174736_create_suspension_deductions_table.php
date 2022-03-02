<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuspensionDeductionsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('suspension_deductions', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('suspension_id');
				$table->string('date')->nullable();
				$table->integer('days')->nullable()->default('0');
				$table->integer('deducted')->default('0');
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
		