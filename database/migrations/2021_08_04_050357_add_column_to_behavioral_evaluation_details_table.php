<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToBehavioralEvaluationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('behavioral_evaluation_details');
        Schema::create('behavioral_evaluation_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bsc_evaluation_id');
            $table->integer('behavioral_sub_metric_id');
            $table->decimal('actual',10,2)->nullable();
            $table->decimal('self_assessment',10,2)->nullable();
            $table->decimal('manager_assessment',10,2)->nullable();
            $table->decimal('manager_of_manager_assessment',10,2)->nullable();
            $table->integer('appraisee_approved')->nullable();
            $table->text('comment')->nullable();
            $table->text('key_deliverable')->nullable();
            $table->text('head_of_strategy')->nullable();
            $table->text('head_of_hr')->nullable();
            $table->string('accept_reject')->nullable();
            $table->timestamps();
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('behavioral_evaluation_details');
    }
}
