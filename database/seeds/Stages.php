<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Stages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('stages')->truncate();
        DB::statement("INSERT INTO `stages` (`id`, `name`, `workflow_id`, `type`, `user_id`, `role_id`, `group_id`, `position`, `created_at`, `updated_at`) VALUES (1, 'Stage - 1', 1, 1, 1, 0, 0, 0, '2020-09-29 20:21:07', '2020-09-29 20:21:07'), (2, 'Stage - 2', 1, 2, 0, 1, 0, 1, '2020-09-29 20:21:07', '2020-09-29 20:21:07'), (3, 'Stage - 1', 2, 1, 1, 0, 0, 0, '2020-10-19 21:57:00', '2020-10-19 21:57:00'), (4, 'Stage - 2', 2, 2, 0, 1, 0, 1, '2020-10-19 21:57:00', '2020-10-19 21:57:00');");       
    }
}
