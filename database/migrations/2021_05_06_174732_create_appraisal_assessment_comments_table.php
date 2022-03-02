<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppraisalAssessmentCommentsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('appraisal_assessment_comments', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('appraisal_id');
				$table->integer('appraisal_comment_id');
				$table->text('comment');
				$table->integer('created_by');
				$table->integer('updated_by');
				$table->integer('company_id');
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
		