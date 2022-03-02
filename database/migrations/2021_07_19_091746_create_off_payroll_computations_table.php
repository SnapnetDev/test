<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffPayrollComputationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //['off_payroll_item_id','year']
        Schema::create('off_payroll_computations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('off_payroll_item_id');
            $table->integer('year');
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
        Schema::dropIfExists('off_payroll_computations');
    }
}
