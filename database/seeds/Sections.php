<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Sections extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('user_sections')->truncate();
         DB::statement("INSERT INTO user_sections (id, name, company_id) VALUES ('1', 'Full Staffs', '1');");    
    }
}
