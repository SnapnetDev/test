<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerformanceDiscussionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('performance_discussion_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('performance_discussion_id');
            $table->integer('evaluation_detail_id');
            $table->text('action_update')->nullable();
            $table->text('challenges')->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('performance_discussion_details');
    }
}
