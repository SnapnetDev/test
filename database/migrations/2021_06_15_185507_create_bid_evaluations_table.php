<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBidEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bid_evaluations', function (Blueprint $table) {
            $table->increments('id');
            $table->date('submission_of_bid_evalueation_report');
            $table->date('ppa_issue_certificate_of_completion');
            $table->unsignedInteger('procurement_item_id');
            $table->foreign('procurement_item_id')
                ->references('id')
                ->on('procurement_items')
                ->onDelete('cascade');
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
        Schema::dropIfExists('bid_evaluations');
    }
}
