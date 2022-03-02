<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToBscEvaluationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


            Schema::dropIfExists('bsc_evaluation_details');
            Schema::create('bsc_evaluation_details', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('bsc_evaluation_id');
                $table->integer('metric_id');
                $table->text('focus')->nullable();
                $table->text('key_deliverable')->nullable();
                $table->text('measure_of_success')->nullable();
                $table->text('means_of_verification')->nullable();
                $table->integer('is_penalty');
                $table->text('objective')->nullable();
                $table->string('accept_reject')->nullable();
                $table->text('evaluator_id')->nullable();
                $table->decimal('target',10,2)->nullable();
                $table->decimal('achievement')->nullable();
                $table->decimal('self_assessment')->nullable();
                $table->decimal('weight')->nullable();
                $table->decimal('manager_assessment')->nullable();
                $table->decimal('manager_of_manager_assessment')->nullable();
                $table->text('justification_of_rating')->nullable();
                $table->text('head_of_hr_comment')->nullable();
                $table->text('head_of_strategy_comment')->nullable();
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
        Schema::dropIfExists('bsc_evaluation_details');
    }
}
