<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyBscDetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bsc_det_details');
        Schema::create('bsc_det_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bsc_det_id');
            $table->integer('metric_id');
            $table->text('business_goal');
            $table->integer('is_penalty');
            $table->text('performance_metric_description');
            $table->decimal('target',10,2)->nullable();
            $table->decimal('weight')->nullable();
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
        Schema::dropIfExists('bsc_det_details');
    }
}
