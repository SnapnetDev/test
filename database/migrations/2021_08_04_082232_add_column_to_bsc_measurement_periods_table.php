<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToBscMeasurementPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       // $fillable=['from','to','status','head_of_hr_id','head_of_strategy_id'];
        Schema::table('bsc_measurement_periods', function (Blueprint $table) {
            $table->integer('status')->nullable();
            $table->integer('head_of_hr_id')->nullable();
            $table->integer('head_of_strategy_id')->nullable();
            $table->decimal('scorecard_percentage',5,2)->nullable();
            $table->decimal('behavioral_percentage',5,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bsc_measurement_periods', function (Blueprint $table) {
           $table->dropColumn(['status','head_of_hr_id','head_of_strategy_id','scorecard_percentage','behavioral_percentage']);
        });
    }
}
