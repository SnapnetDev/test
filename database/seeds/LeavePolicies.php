<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeavePolicies extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
          DB::table('leave_policies')->truncate();
         DB::statement("INSERT INTO `leave_policies` (`id`, `includes_weekend`, `includes_holiday`, `workflow_id`, `company_id`, `user_id`, `created_at`, `updated_at`, `default_length`, `uses_spillover`, `uses_maximum_spillover`, `spillover_length`, `spillover_month`, `spillover_day`, `relieve_approves`, `probationer_applies`, `uses_casual_leave`, `casual_leave_length`) VALUES (1, 0, 0, 0, 1, 1, '2019-01-19 13:50:42', '2020-09-23 20:46:56', 19, 1, 1, 5, 12, 31, 0, 0, 0, 0);");       
    }
}
