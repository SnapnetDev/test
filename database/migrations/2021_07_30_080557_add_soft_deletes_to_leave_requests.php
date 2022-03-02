<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesToLeaveRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_requests', function ($table) {
            //add leave requested allowance
            $table->integer('requested_allowance')->nullable();
            $table->softDeletes();
        });
        Schema::table('leave_request_dates', function ($table) {
            $table->softDeletes();
        });
        Schema::table('leave_policies', function ($table) {
            $table->integer('can_request_allowance')->nullable();
        });
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
