<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Branches extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('branches')->truncate();
        DB::statement("INSERT INTO `branches` (`id`, `name`, `address`, `email`, `company_id`, `manager_id`, `created_at`, `updated_at`) VALUES (1, 'Lagos', NULL, NULL, 5, 1, '2018-07-06 11:24:40', '2018-07-06 11:24:40'), (2, 'Lagos', NULL, NULL, 11, 1, '2018-07-06 11:24:40', '2018-07-06 11:24:40'), (3, 'Lagos', NULL, NULL, 13, 1, '2018-07-06 11:24:40', '2018-07-06 11:24:40'), (4, 'Abuja', NULL, NULL, 13, 1, '2018-07-06 11:24:40', '2018-07-06 11:24:40'), (5, 'Adamawa', NULL, NULL, 14, 1, '2018-07-06 11:24:40', '2018-07-06 11:24:40'), (6, 'Adamawa', NULL, NULL, 16, 1, '2018-07-06 11:24:40', '2018-07-06 11:24:40'), (7, 'Kwara', NULL, NULL, 15, 1, '2018-07-06 11:24:40', '2018-07-06 11:24:40'), (8, 'Lagos', NULL, NULL, 8, 1, '2018-07-06 11:24:40', '2018-07-06 11:24:40'), (9, 'Lagos', NULL, NULL, 9, 1, '2018-07-06 11:24:40', '2018-07-06 11:24:40'), (10, 'Lagos', NULL, NULL, 10, 1, '2018-07-06 11:24:40', '2018-07-06 11:24:40');");        
    }
}
