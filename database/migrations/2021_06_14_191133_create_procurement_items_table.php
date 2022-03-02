<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcurementItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->unsignedInteger('goods_procurement_id');
            $table->foreign('goods_procurement_id')
                ->references('id')
                ->on('goods_procurements')
                ->onDelete('cascade');
            $table->string('package_number');
            $table->unsignedTinyInteger('lot_number');
            $table->unsignedSmallInteger('no_of_units');
            $table->double('budget_available', 19, 2);
            $table->char('approval_threshold', 20);
            $table->char('procurement_method', 20);
            $table->char('pre_or_post_qualification', 20);
            $table->char('prior_or_review', 20);
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
        Schema::dropIfExists('procurement_items');
    }
}
