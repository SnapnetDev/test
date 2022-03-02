<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletFundHistoryTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('wallet_fund_history', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('user_id');
				$table->string('amount');
				$table->string('status');
				$table->integer('company_id')->default('1');
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
		