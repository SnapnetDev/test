<?php

namespace App\Http\Controllers\Auth;

use App\Department;
use App\Grade;
use App\Job;
use App\Notifications\NewUserCreatedNotify;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Company;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $company=Company::create(['name'=>$data['company_name'],'email'=>$data['company_email'],'address'=>$data['company_address']]);
        $grade=Grade::create(['level'=>$data['grade'],'company_id'=>$company->id]);
        $department=Department::create(['name'=>$data['department'],'company_id'=>$company->id]);
        $job_role=Job::create(['title'=>$data['job_role'],'department_id'=>$department->id]);


        $user = User::create([
            'name' => $data['first_name'].' '.$data['last_name'],
            'email' => $data['email'],
            'emp_num'=>$data['emp_num'],
            'sex'=>$data['gender'],
            'emp_num'=>$data['emp_num'],
            'hire_date'=>date('Y-m-d',strtotime($data['hiredate'])),
            'company_id'=>$company->id,
            'job_id'=> $job_role->_id,
            'department_id'=> $department->id,
            'grade_id'=>$grade->id,
            'role_id'=>9,
            'payroll_type'=>'office'
        ]);
        $user->notify( new NewUserCreatedNotify($user));
        return $user;
    }
}
