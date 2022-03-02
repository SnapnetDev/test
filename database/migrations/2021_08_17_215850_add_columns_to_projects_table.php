<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('projects');
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('fund_code')->nullable();
            $table->text('remark')->nullable();
            $table->text('description')->nullable();
            $table->string('client_name')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_est_date')->nullable();
            $table->date('actual_ending_date')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('project_manager_id')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
