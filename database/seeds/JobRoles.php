<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('jobroles')->truncate();
         DB::statement("INSERT INTO `jobroles` (`id`, `title`, `description`, `department_id`, `qualification_id`, `parent_id`, `personnel`, `created_at`, `updated_at`) VALUES (1, 'Nuclear Technician', 'Nuclear Technician', 1, NULL, NULL, 4, '2018-12-04 13:42:09', '2018-12-04 13:42:09'), (2, 'Information Systems Manager', 'Information Systems Manager', 1, NULL, NULL, 2, '2018-12-04 13:42:09', '2018-12-04 13:42:09'), (3, 'Tire Builder', 'Tire Builder', 1, NULL, NULL, 1, '2018-12-04 13:42:09', '2018-12-04 13:42:09'), (4, 'Music Composer', 'Music Composer', 1, NULL, NULL, 4, '2018-12-04 13:42:09', '2018-12-04 13:42:09'), (5, 'Steel Worker', 'Steel Worker', 1, NULL, NULL, 1, '2018-12-04 13:42:09', '2018-12-04 13:42:09'), (6, 'Graphic Designer', 'Graphic Designer', 1, NULL, NULL, 4, '2018-12-04 13:42:09', '2018-12-04 13:42:09'), (7, 'Radiologic Technologist', 'Radiologic Technologist', 1, NULL, NULL, 2, '2018-12-04 13:42:09', '2018-12-04 13:42:09');");        
    }
}
