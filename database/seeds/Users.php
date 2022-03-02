<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->truncate();
        DB::statement("INSERT INTO `users` (`id`, `emp_num`, `name`, `email`, `password`, `sex`, `dob`, `phone`, `marital_status`, `image`, `address`, `hiredate`, `job_id`, `role_id`, `branch_id`, `company_id`, `superadmin`, `status`, `remember_token`, `session_id`, `created_at`, `updated_at`, `country_id`, `state_id`, `lga_id`, `bank_id`, `bank_account_no`, `employment_status`, `staff_category_id`, `department_id`, `grade_id`, `line_manager_id`, `payroll_type`, `project_salary_category_id`, `last_login_at`, `last_login_ip`, `union_id`, `section_id`, `expat`, `non_payroll_provision_employee`, `confirmation_date`, `image_id`, `probation_period`, `last_promoted`, `active`, `first_name`, `middle_name`, `last_name`) VALUES (1, '1001', 'System Administrator', 'admin@snapnet.com.ng', '\$2y\$10\$mxkoUCFI32VeheqEopMN8.qG/ARsCRZSOlnQ0sFXxaxjuE08JxZ5u', 'M', '1988-01-27', '7036725297', 'Single', '/M5AgVngPeAsTvG5dLY11c5KZXEgSjkw0nB5ZeC0f.jpeg', '30, Richard Aigbe Crescent, Olomu Town off Isawo Road, Ikorodu, Abuja.', '2018-01-27', 1, 1, 23, 1, 1, 1, 'wWTgnOZlxE514phiv2UktGJQEI0S2TYlVZoPLOdbx4okY0QGhet3bc5ETHjY', 'rj68DBI6JZ96OMelZ8zH1545zHcfIQsXyEEOrcpO', '2018-11-24 06:28:17', '2021-02-18 10:46:41', 160, 2654, 48445, 59, '9637383345', 1, NULL, 0, 3, 0, 'office', 10, '2021-02-18 10:46:40', '172.68.185.23', 0, 1, 1, 1, '1970-01-01', NULL, '0', NULL, 1, 'System', NULL, 'Administrator'), (2, '007', 'Anya Edmund  Duroha', 'linemanager@snapnet.com.ng', '\$2y\$10\$mxkoUCFI32VeheqEopMN8.qG/ARsCRZSOlnQ0sFXxaxjuE08JxZ5u', 'M', '1964-10-30', '08033900700', 'Married', 'upload/avatar.jpg', 'C125 NICON TOWN, LEKKI', '2017-09-01', 0, 4, 23, 1, 0, 2, NULL, NULL, '2020-08-28 10:12:30', '2021-01-04 08:52:57', 0, 0, 0, 59, '1003010029-N', 1, NULL, 0, 0, 0, NULL, 0, NULL, NULL, 0, 0, 0, 0, NULL, NULL, '0', NULL, 1, 'Anya', 'Edmund', 'Duroha'), (3, '003', 'Godwin Anene  Ojali', 'employee@snapnet.com.ng', '\$2y\$10\$mxkoUCFI32VeheqEopMN8.qG/ARsCRZSOlnQ0sFXxaxjuE08JxZ5u', 'M', '1965-01-06', '08023276324', 'Married', 'upload/avatar.jpg', '11 Victor Ndalugi avenue, Uba Estate, Satellite Town, Lagos', '2017-09-01', 473, 4, 23, 1, 0, 1, NULL, NULL, '2020-08-28 10:12:38', '2021-01-04 08:52:59', 0, 0, 0, 59, '1003010256-N', 1, NULL, 0, 66, 468, 'project', 31, NULL, NULL, 0, 0, 0, 0, '2018-03-01', NULL, '0', NULL, 1, 'Godwin', 'Anene', 'Ojali');");
    }
}
