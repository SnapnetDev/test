<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiddingPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bidding_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->date('bid_rep_submission_by_mdas');
            $table->date('ppa_no_objection_date')->nullable();
            $table->date('bid_invitation_date');
            $table->date('bid_closing_and_opening');
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
        Schema::dropIfExists('bidding_periods');
    }
}
