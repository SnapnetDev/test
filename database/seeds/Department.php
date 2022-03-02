<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Department extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('departments')->truncate();
        DB::statement("INSERT INTO `departments` (`id`, `name`, `company_id`, `manager_id`, `created_at`, `updated_at`) VALUES (1, 'Corporate Services', 1, 1, '2020-08-24 13:26:48', '2020-10-15 19:35:35');");
    }
}
