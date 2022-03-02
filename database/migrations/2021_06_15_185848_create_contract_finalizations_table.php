<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractFinalizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_finalizations', function (Blueprint $table) {
            $table->increments('id');
            $table->double('contact_amount', 19, 2);
            $table->date('notification_of_award');
            $table->date('contract_award');
            $table->date('mobilization_advanced_payment');
            $table->date('substantial_completion_install');
            $table->date('inspection_and_final_acceptance');
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
        Schema::dropIfExists('contract_finalizations');
    }
}
