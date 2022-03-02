<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('companies', 'bc_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('bc_id')->nullable();
            });
        }
        if (!Schema::hasColumn('users', 'bc_sync')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('bc_sync')->nullable();
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
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('bc_id');

        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('bc_sync');

        });
    }
}
