<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 9/28/2020
 * Time: 8:09 AM
 */

namespace App\Services;


use App\SpecificSalaryComponent;
use App\User;

class SpecificSalaryWriter
{

	function writeConfig($data=[],User $user){
		//'name',
		//'gl_code', ''
		//'project_code',''
		//'type', deduction
		//'comment',''
		//'emp_id',
		//'duration',
		//'grants',0
		//'status',1
		//'starts',
		//'ends',
		//'company_id',
		//'amount',
		//'taxable',0
		//'taxable_type',''
		//'specific_salary_component_type_id',''
		//'completed','0'
		//'one_off','0'

		$obj = new SpecificSalaryComponent;

		$obj->name = 'Loan-Repayment-Component-' . $user->name;
//		$obj->gl_code = '';
//		$obj->project_code = '';
		$obj->type = 0; //'deduction';
		$obj->comment = '';
		$obj->emp_id = $user->id;
		$obj->duration = 1;// $data['duration']; raises payroll index not found exception if duration is greater than 2.
		$obj->grants = 0;
		$obj->status = 1;
//		$obj->starts = '';
//		$obj->ends = '';
		$obj->company_id = companyId();
		$obj->amount = $data['amount'];
		$obj->taxable = 0;
//		$obj->taxable_type = '';
		$obj->specific_salary_component_type_id = $data['specific_salary_component_type_id'];
		$obj->completed = 0;
		$obj->one_off = 0;
		$obj->loan_id = $data['loan_id'];

		$obj->save();

//		 SpecificSalaryComponent

	}

}