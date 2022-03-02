<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('wallets', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name');
				$table->string('lock_code');
				$table->string('user_ref');
				$table->integer('created_by');
				$table->string('amount');
				$table->string('currency')->default('NGN');
				$table->integer('wallet_id');
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
		