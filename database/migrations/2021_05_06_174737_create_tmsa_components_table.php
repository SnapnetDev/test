<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmsaComponentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('tmsa_components', function(Blueprint $table){
			$table->increments('id');	
				$table->string('name')->nullable();
				$table->string('constant')->nullable();
				$table->decimal('amount', 10, 2)->nullable();
				$table->integer('status')->default('0');
				$table->integer('taxable')->default('0');
				$table->integer('type')->default('0');
				$table->string('gl_code')->nullable();
				$table->string('project_code')->nullable();
				$table->text('comment')->nullable();
				$table->integer('company_id')->default('0');
				$table->integer('fixed')->default('0');
				$table->string('formula')->nullable();
				$table->integer('uses_month')->default('0');
				$table->integer('year')->nullable();
				$table->decimal('rate', 10, 2)->nullable();
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
		