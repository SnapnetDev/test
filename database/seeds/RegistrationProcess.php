<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationProcess extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('registration_progresses')->truncate();
         DB::statement("INSERT INTO `registration_progresses` (`id`, `has_users`, `has_grades`, `has_leave_policy`, `has_payroll_policy`, `has_branches`, `has_departments`, `has_job_roles`, `company_id`, `completed`, `created_at`, `updated_at`) VALUES (1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2021-04-05 10:34:53', '2021-04-05 10:34:53');");        
    }
}
