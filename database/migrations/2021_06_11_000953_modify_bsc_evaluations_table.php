<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyBscEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bsc_evaluations');
        Schema::create('bsc_evaluations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bsc_measurement_period_id');
            $table->integer('user_id');
            $table->integer('department_id');
            $table->integer('performance_category_id');
            $table->text('score')->nullable();
            $table->decimal('self_score',10,2)->nullable();
            $table->decimal('penalty_score',10,2)->nullable();
            $table->decimal('weight_sum',10,2)->nullable();
            $table->integer('manager_id')->nullable();
            $table->text('employee_strength')->nullable();
            $table->text('employee_developmental_area')->nullable();
            $table->text('special_achievement')->nullable();
            $table->text('manager_approval_comment')->nullable();
            $table->timestamp('manager_approval_date')->nullable();
            $table->integer('manager_approval_approved')->nullable();
            $table->integer('final_approver_id')->nullable();
            $table->integer('final_approval_approved')->nullable();
            $table->text('final_approver_comment')->nullable();
            $table->timestamp('final_approver_date')->nullable();
            $table->integer('kpi_submitted')->nullable();
            $table->timestamp('kpi_submitted_date')->nullable();
            $table->integer('kpi_accepted')->nullable();
            $table->timestamp('kpi_accepted_date')->nullable();
            $table->integer('company_id')->nullable();
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
        Schema::dropIfExists('bsc_evaluations');
    }
}
