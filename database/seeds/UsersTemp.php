<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTemp extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('user_temps')->truncate();
        DB::statement("INSERT INTO `user_temps` (`id`, `emp_num`, `name`, `first_name`, `middle_name`, `last_name`, `email`, `password`, `sex`, `dob`, `phone`, `marital_status`, `image`, `address`, `hiredate`, `job_id`, `role_id`, `branch_id`, `company_id`, `superadmin`, `status`, `remember_token`, `created_at`, `updated_at`, `country_id`, `state_id`, `lga_id`, `bank_id`, `bank_account_no`, `employment_status`, `staff_category_id`, `department_id`, `grade_id`, `line_manager_id`, `payroll_type`, `project_salary_category_id`, `last_login_at`, `last_login_ip`, `union_id`, `section_id`, `expat`, `non_payroll_provision_employee`, `confirmation_date`, `image_id`, `last_change_approved_on`, `last_change_approved`, `last_change_approved_by`, `last_promoted`, `user_id`) VALUES (1, '007', 'Anya Edmund  Duroha', 'Anya', 'Edmund', 'Duroha', 'linemanager@snapnet.com.ng', '\$2y\$10\$mxkoUCFI32VeheqEopMN8.qG/ARsCRZSOlnQ0sFXxaxjuE08JxZ5u', 'M', '1964-10-30', '08033900700', 'Married', 'upload/avatar.jpg', 'C125 NICON TOWN, LEKKI', '2017-09-01', 0, 4, 23, 1, 0, 0, NULL, '2020-08-28 10:12:30', '2021-01-04 10:36:36', 0, 0, 0, 59, '1003010029-N', 1, NULL, 0, 0, 0, NULL, 0, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, 1, 0, NULL, 2), (2, '003', 'Godwin Anene  Ojali', 'Godwin', 'Anene', 'Ojali', 'employee@snapnet.com.ng', '\$2y\$10\$mxkoUCFI32VeheqEopMN8.qG/ARsCRZSOlnQ0sFXxaxjuE08JxZ5u', 'M', '1965-01-06', '08023276324', 'Married', 'upload/avatar.jpg', '11 Victor Ndalugi avenue, Uba Estate, Satellite Town, Lagos', '2017-09-01', 473, 4, 23, 1, 0, 0, NULL, '2020-08-28 10:12:38', '2021-01-04 10:36:38', 0, 0, 0, 59, '1003010256-N', 1, NULL, 0, 68, 0, NULL, 0, NULL, NULL, 0, 0, 0, 0, '2018-03-01', NULL, NULL, 1, 0, NULL, 3);");

    }
}
