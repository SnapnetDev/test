<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Workflow extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('workflows')->truncate();
        DB::statement("INSERT INTO `workflows` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES (1, 'Loan Workflow', 1, '2020-09-29 20:21:07', '2020-09-29 20:21:07'), (2, 'Training Workflow', 1, '2020-10-19 21:57:00', '2020-10-19 21:57:00');");        
    }
}
