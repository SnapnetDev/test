<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyBscEvaluationDetailsTable extends Migration
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
            $table->text('business_goal');
            $table->integer('is_penalty');
            $table->text('performance_metric_description');
            $table->text('evaluator_id')->nullable();
            $table->decimal('target',10,2)->nullable();
            $table->decimal('achievement')->nullable();
            $table->decimal('score')->nullable();
            $table->decimal('weight')->nullable();
            $table->decimal('self_score')->nullable();
            $table->decimal('final_score')->nullable();
            $table->text('justification_of_rating')->nullable();
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
