<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNPIndividualKPIsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('n_p_individual_k_p_is', function(Blueprint $table){
			$table->increments('id');	
				$table->string('n_p_user_id');
				$table->text('kpi_question');
				$table->string('weight')->default('0');
				$table->string('target')->default('0');
				$table->string('actual')->default('0');
				$table->string('score')->default('0');
				$table->text('kpi_rating')->nullable();
				$table->string('kpi_rating_type')->nullable();
				$table->text('measurement')->nullable();
				$table->text('data_source')->nullable();
				$table->text('frequency_of_data')->nullable();
				$table->text('responsible_collation_unit')->nullable();
				$table->text('user_comment')->nullable();
				$table->text('manager_comment')->nullable();
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
		