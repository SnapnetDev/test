<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Grades extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('grades')->truncate();
         DB::statement("INSERT INTO `grades` (`id`, `level`, `grade_category_id`, `bsc_grade_performance_category_id`, `basic_pay`, `leave_length`, `payroll_policy_id`, `company_id`, `created_at`, `updated_at`, `description`, `pos`) VALUES (1, 'BAND 1', 0, 0, NULL, 30, NULL, 1, '2021-01-24 15:49:20', '2021-01-24 15:49:20', 'Paternity leave- 10days\nCompassionate leave- maximum 5 days \nExam  leave- 5 leave\nStudy leave- after 4 years in the company, eligible for 2 years study leave', NULL);");   
    }
}
