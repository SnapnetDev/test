<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Skills extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('skills')->truncate();
         DB::statement("INSERT INTO `skills` (`id`, `name`, `created_at`, `updated_at`) VALUES (1, 'PHP', NULL, NULL), (2, 'JavaScript', NULL, NULL), (3, 'Ruby', '2018-11-28 12:09:32', '2018-11-28 12:09:32'), (4, 'Management', '2018-11-28 15:03:17', '2018-11-28 15:03:17'), (5, 'Java', '2018-11-28 15:15:30', '2018-11-28 15:15:30');");       
    }
}
