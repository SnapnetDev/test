<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_banks', function (Blueprint $table) {
            //$fillable=['user_id','year','entitled','used','last_modified_by','company_id'];
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('year')->nullable();
            $table->integer('entitled');
            $table->integer('used')->nullable();
            $table->integer('last_modified_by')->nullable();
            $table->integer('company_id')->nullable();
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
        Schema::dropIfExists('leave_banks');
    }
}
