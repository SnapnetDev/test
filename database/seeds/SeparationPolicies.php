<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeparationPolicies extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('separation_policies')->truncate();
         DB::statement("INSERT INTO `separation_policies` (`id`, `employee_fills_form`, `use_approval_process`, `prorate_salary`, `notify_staff_on_exit`, `workflow_id`, `company_id`, `created_at`, `updated_at`) VALUES (1, 1, 1, 1, 0, 2, 1, '2021-04-13 14:29:45', '2021-04-14 11:40:19');");        
    }
}
