<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsReliefColumnToPaceSalaryComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pace_salary_components', function (Blueprint $table) {
            $table->integer('is_relief')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pace_salary_components', function (Blueprint $table) {
            $table->dropColumn('is_relief');
        });
    }
}
