<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffPayrollItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         $fillable=['off_payroll_type_id','name','source','salary_component_constant',
        'payroll_constant','amount','is_prorated','proration_type','percentage'];
        Schema::create('off_payroll_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('off_payroll_type_id')->nullable();
            $table->string('name')->nullable();
            $table->string('source')->nullable();
            $table->string('salary_component_constant')->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->integer('is_prorated')->nullable();
            $table->integer('proration_type')->nullable();
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
        Schema::dropIfExists('off_payroll_items');
    }
}
