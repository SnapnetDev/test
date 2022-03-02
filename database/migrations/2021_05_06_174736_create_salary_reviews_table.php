<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryReviewsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('salary_reviews', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('employee_id');
				$table->integer('company_id');
				$table->string('review_month');
				$table->string('payment_month');
				$table->integer('used');
				$table->integer('review_month_day')->nullable();
				$table->integer('review_type')->default('1');
				$table->integer('previous_salary_category_id')->nullable();
				$table->decimal('previous_gross', 10, 2)->nullable();
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
		