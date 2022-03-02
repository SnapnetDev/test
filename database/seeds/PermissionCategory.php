<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionCategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('permission_categories')->truncate();
        DB::statement("INSERT INTO `permission_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES (1, 'People Analytics', '2018-07-15 20:00:00', '2018-07-15 20:00:00'), (4, 'Attendance', '2018-07-15 20:00:00', '2018-07-15 20:00:00'), (5, 'Leave', '2018-07-15 20:00:00', '2018-07-15 20:00:00'), (9, 'Employee Management', '2018-07-15 20:00:00', '2018-07-15 20:00:00'), (13, 'Settings', '2018-07-15 20:00:00', '2018-07-15 20:00:00'), (14, 'Audit', '2018-07-15 20:00:00', '2018-07-15 20:00:00'), (16, 'Project Management', '2018-08-26 23:00:00', '2018-08-26 23:00:00'), (17, 'Dashboard', '2018-08-26 23:00:00', '2018-08-26 23:00:00'), (18, 'Payroll', '2018-08-26 23:00:00', '2018-08-27 16:00:00'), (19, 'Loan', '2018-08-26 23:00:00', '2018-08-26 23:00:00'), (20, 'Document', '2018-08-26 23:00:00', '2018-08-26 23:00:00'), (21, 'Performance', '2018-08-26 23:00:00', '2018-08-26 23:00:00'), (22, 'E - Learning', '2020-03-31 08:11:00', '2020-03-31 08:11:00'), (23, 'Probation', '2020-03-31 08:18:40', '2020-03-31 08:18:40'), (24, 'Import', '2020-06-01 08:29:55', '2020-06-01 08:29:55'), (25, 'Polls', '2020-06-01 08:29:55', '2020-06-01 08:29:55');");
    }
}
