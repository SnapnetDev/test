<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySpecificSalaryComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('specific_salary_components', 'leave_allowance_payment_id')) {
            Schema::table('specific_salary_components', function (Blueprint $table) {
                $table->integer('leave_allowance_payment_id')->nullable();

            });

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
