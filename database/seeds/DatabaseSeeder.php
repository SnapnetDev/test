<?php

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(Companies::class);
        $this->call(Users::class);
        $this->call(UsersTemp::class);
        $this->call(Roles::class);
        $this->call(Workflow::class);
        $this->call(Stages::class);
        $this->call(PayrollPolicies::class);
        $this->call(Branches::class);
        $this->call(Grades::class);
        $this->call(Countries::class);
        $this->call(States::class);
        $this->call(LGA::class);
        $this->call(Sections::class);
        $this->call(Banks::class);
        $this->call(Skills::class);
        $this->call(JobRoles::class);
        $this->call(SalaryComponents::class);
        $this->call(LeavePolicies::class);
        $this->call(Department::class);
        $this->call(Permissions::class);
        $this->call(PermissionRole::class);
        $this->call(PermissionCategory::class);
        $this->call(SeparationPolicies::class);
        $this->call(RegistrationProcess::class);
    }
}
