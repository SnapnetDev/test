<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTimesheetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('project_timesheet_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('lin_code');
            $table->integer('project_id');
            $table->integer('hours');
            $table->date('date');
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
        Schema::dropIfExists('project_timesheet_details');
    }
}
