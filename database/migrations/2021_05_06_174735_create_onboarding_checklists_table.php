<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnboardingChecklistsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('onboarding_checklists', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('parent_id')->nullable();
				$table->integer('step')->nullable();
				$table->string('action')->nullable();
				$table->integer('assigned_personnel_id')->nullable();
				$table->string('time')->nullable();
				$table->string('duration')->nullable();
				$table->string('document_template')->nullable();
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
		