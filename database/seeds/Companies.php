<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Companies extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('companies')->truncate();
        DB::statement("INSERT INTO `companies` (`id`, `name`, `email`, `address`, `user_id`, `logo`, `is_parent`, `created_at`, `updated_at`, `color`, `biometric_serial`) VALUES (1, 'Snapnet Limited', 'info@snapnet.com.ng', '17A Dele Adedeji Off Shoungua Cresent, Lekki Phase I, Nigeria.', 1, '/aU7dbsySYG3sy9NPNXj8PkKUPowPK9rDjUikGTFH.png', 1, '2018-07-05 12:47:49', '2019-03-04 17:11:40', '03a9f4', NULL);");
    }
}
