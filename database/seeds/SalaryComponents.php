<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalaryComponents extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('salary_components')->truncate();
         DB::statement("INSERT INTO `salary_components` (`id`, `name`, `constant`, `type`, `formula`, `gl_code`, `project_code`, `comment`, `status`, `company_id`, `taxable`, `created_at`, `updated_at`) VALUES (1, 'Transport Allowance', 'transport_allowance', '1', 'gross_pay * 0.0025', '6413000', 'P1700', NULL, '1', 1, 1, '2018-08-26 23:00:00', '2019-01-26 08:41:47'), (2, 'Utility Allowance', 'utility_allowance', '1', 'basic_pay * 0.375', '6413000', 'P1700', NULL, '0', 1, 1, NULL, '2019-01-26 08:42:13'), (3, 'Housing Allowance', 'housing_allowance', '1', 'basic_pay * 0.75', '6413000', 'P1700', NULL, '0', 1, 1, NULL, '2019-04-25 17:48:58'), (4, 'Pension Funds', 'pension_fund', '0', '(basic_pay + housing_allowance + transport_allowance) * (0.08)', '6451401', 'P1700', NULL, '1', 1, 0, NULL, '2019-05-23 15:04:30'), (5, 'N.H. Fund', 'nh_fund', '0', 'basic_pay * 0.025', '4210000', 'P1700', NULL, '0', 1, 1, NULL, '2019-01-28 00:04:30'), (6, 'NSITF', '13th_month', '0', 'gross_salary * 0.023', '4210000', 'P1700', NULL, '0', 1, 0, NULL, '2019-03-14 16:16:42');");        
    }
}
