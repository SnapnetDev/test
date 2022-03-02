<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffPayrollItemComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //'off_payroll_item_id','name','source',
    //'salary_component_constant','payroll_constant','amount','percentage'
    public function up()
    {
        Schema::create('off_payroll_item_components', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('off_payroll_item_id');
            $table->string('name');
            $table->string('source');
            $table->string('salary_component_constant')->nullable();
            $table->string('payroll_constant')->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->integer('percentage')->nullable();
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
        Schema::dropIfExists('off_payroll_item_components');
    }
}
