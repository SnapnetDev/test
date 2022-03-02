<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayrollPolicies extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('payroll_policies')->truncate();
         DB::statement("INSERT INTO `payroll_policies` (`id`, `payroll_runs`, `basic_pay_percentage`, `user_id`, `workflow_id`, `use_office`, `use_tmsa`, `use_project`, `use_lateness`, `show_all_gross`, `display_lsa_on_nav_export`, `display_lsa_on_payroll_export`, `company_id`, `created_at`, `updated_at`, `uses_approval`, `suspension_prorates`, `new_hire_prorates`, `separation_prorates`, `leave_spill_is_paid`) VALUES (1, 1, '40.0000000', 1, 1, 1, 0, 0, 0, 1, 0, 0, NULL, '2018-08-20 08:44:39', '2018-10-24 06:13:57', 0, 1, 1, 1, 1);");
    }
}
