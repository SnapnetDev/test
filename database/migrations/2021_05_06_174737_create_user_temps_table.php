<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTempsTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('user_temps', function(Blueprint $table){
			$table->increments('id');	
				$table->string('emp_num')->nullable();
				$table->string('name')->nullable();
				$table->string('first_name')->nullable();
				$table->string('middle_name')->nullable();
				$table->string('last_name')->nullable();
				$table->string('email')->nullable();
				$table->string('password')->default('$2y$10$mxkoUCFI32VeheqEopMN8.qG/ARsCRZSOlnQ0sFXxaxjuE08JxZ5u');
				$table->string('sex')->nullable();
				$table->string('dob')->nullable();
				$table->string('phone')->nullable();
				$table->string('marital_status')->nullable();
				$table->string('image')->default('upload/avatar.jpg');
				$table->string('address')->nullable();
				$table->string('hiredate')->nullable();
				$table->integer('job_id')->nullable();
				$table->integer('role_id')->default('0');
				$table->integer('branch_id')->nullable();
				$table->integer('company_id')->default('0');
				$table->integer('superadmin')->default('0');
				$table->integer('status')->default('0');
				$table->string('remember_token')->nullable();
				$table->integer('country_id')->default('0');
				$table->integer('state_id')->default('0');
				$table->integer('lga_id')->default('0');
				$table->integer('bank_id')->default('0');
				$table->string('bank_account_no')->nullable();
				$table->integer('employment_status')->default('1');
				$table->integer('staff_category_id')->nullable();
				$table->integer('department_id')->default('0');
				$table->integer('grade_id')->nullable()->default('0');
				$table->integer('line_manager_id')->default('0');
				$table->string('payroll_type')->nullable();
				$table->integer('project_salary_category_id')->nullable()->default('0');
				$table->string('last_login_at')->nullable();
				$table->string('last_login_ip')->nullable();
				$table->integer('union_id')->default('0');
				$table->integer('section_id')->default('0');
				$table->integer('expat')->default('0');
				$table->integer('non_payroll_provision_employee')->default('0');
				$table->string('confirmation_date')->nullable();
				$table->string('image_id')->nullable();
				$table->string('last_change_approved_on')->nullable();
				$table->integer('last_change_approved')->default('1');
				$table->integer('last_change_approved_by')->default('0');
				$table->string('last_promoted')->nullable();
				$table->integer('user_id')->default('0');
 				$table->timestamps();
			});
		}

		/**
 * Reverse the migrations.
 *
 * @return void
 */
		public function down()
		{
    //
		}
};
		