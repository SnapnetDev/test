<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyaccountdetailsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('companyaccountdetails', function(Blueprint $table){
			$table->increments('id');	
				$table->string('merchantKey');
				$table->string('apiKey');
				$table->string('enviroment');
				$table->string('accountNum');
				$table->string('account_token')->nullable();
				$table->integer('bank_id')->nullable();
				$table->string('first_name')->nullable();
				$table->string('last_name')->nullable();
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
		