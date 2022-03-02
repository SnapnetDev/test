<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationActiveStatusesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('notification_active_statuses', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('notification_type_id');
				$table->integer('company_id');
				$table->integer('a_id');
				$table->string('status')->default('active');
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
		