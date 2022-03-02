<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->truncate();
        DB::statement("INSERT INTO `roles` (`id`, `name`, `manages`, `created_at`, `updated_at`) VALUES (1, 'Super Admin', 'all', '2018-07-15 20:00:00', '2018-08-27 20:14:11'), (2, 'Hr Admin', 'all', '2018-07-15 20:00:00', '2018-10-25 13:23:24'), (3, 'Line Manager', 'dr', '2018-07-15 20:00:00', '2018-08-24 11:53:25'), (4, 'Employee', 'dr', '2018-07-15 20:00:00', '2020-04-15 06:54:19'), (5, 'IT', NULL, '2018-08-24 11:49:17', '2018-08-24 11:49:17'), (6, 'CFO', 'dr', '2018-10-18 08:19:33', '2018-10-18 08:19:33'), (7, 'Supervisor of Supervisor', 'ss', '2020-09-11 09:47:20', '2020-09-11 09:47:20');");
    }
}
