<?php

namespace App\Http\Controllers;

use App\Company;
use App\Department;
use App\Grade;
use App\Holiday;
use App\Job;
use App\Leave;
use App\LeavePeriod;
use App\LeavePolicy;
use App\Notifications\NewUserCreatedNotify;
use App\PayrollPolicy;
use App\RegistrationProgress;
use App\SalaryComponent;
use App\User;
use App\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyOnboardingController extends Controller
{
    public function index(Request $request)
    {
        $rp=RegistrationProgress::where('company_id',companyId())->first();
        if (!$rp){
            $rp=RegistrationProgress::create(['has_users'=>0,'has_grades'=>0,'has_leave_policy'=>0,'has_payroll_policy'=>0,'has_departments'=>0,'has_job_roles'=>0,'company_id'=>companyId(),'completed'=>0]);
        }
        if($rp->has_grades==0){
            return $this->first_step($rp);
        }elseif ($rp->has_branches==0){
            return $this->second_step($rp);
        }elseif ($rp->has_departments==0){
            return $this->third_step($rp);
        }elseif ($rp->has_job_roles==0){
            return $this->fourth_step($rp);
        }elseif ($rp->has_users==0){
            return $this->fifth_step($rp);
        }elseif ($rp->has_leave_policy==0){
            return $this->sixth_step($rp);
        }elseif ($rp->has_payroll_policy==0){
            return $this->seventh_step($rp);
        }else{
            return redirect(url('home'));
        }
   }

    public function first_step($rp)
    {

        return view('registration_process.first_step',compact('rp'));
   }
    public function second_step($rp)
    {
        return view('registration_process.second_step',compact('rp'));
    }
    public function third_step($rp)
    {
        return view('registration_process.third_step',compact('rp'));
    }
    public function fourth_step($rp)
    {
        return view('registration_process.fourth_step',compact('rp'));
    }
    public function fifth_step($rp)
    {
        return view('registration_process.fifth_step',compact('rp'));
    }
    public function sixth_step($rp)
    {
        $company_id=companyId();
        $lp=LeavePolicy::where('company_id',$company_id)->first();
        $workflows=Workflow::all();
        if (!$lp) {
            $lp=LeavePolicy::create(['includes_weekend'=>0,'includes_holiday'=>0,'user_id'=>Auth::user()->id,'company_id'=>$company_id,'workflow_id'=>0]);
        }
        $holidays=Holiday::where('company_id',$company_id)->get();
        $leaveperiods=LeavePeriod::all();
        $leaves=Leave::all();
        $grades=Grade::doesntHave('leaveperiod')->get();
        return view('registration_process.sixth_step',compact('holidays','leaveperiods','grades','leaves','workflows','lp'));
    }
    public function seventh_step($rp)
    {
        return view('registration_process.seventh_step',compact('rp'));
    }

    public function register(Request $request)
    {

       request()->request->add(['company_email'=>'info@fgh.com','emp_num'=>'rx1234','first_name'=>'Rex','company_address'=>'No 1 Akin street Lagos','grade'=>'grade z']);

        $company=Company::create(['name'=>$request['company_name'],'email'=>$request['company_email'],'address'=>$request['company_address']]);
        $grade=Grade::create(['level'=>$request['grade'],'company_id'=>$company->id]);
        $department=Department::create(['name'=>'HR Admin','company_id'=>$company->id]);
        $job_role=Job::create(['title'=>'HR Admin','department_id'=>$department->id,'qualification_id'=>23]);
        $fakr = \Faker\Factory::create();
        $text = $fakr->text(5);
            $emp_num = $text . mt_rand(111, 999);
            

        $gc = explode(" ", $request['name']);
        $user = User::create([
            'first_name' => $gc[0],
            'middle_name' => $gc[1],
            'last_name' => end($gc),
            'email' => $request['email'],
            'emp_num'=>$emp_num,
            'sex'=>'M',
            'hire_date'=>date('Y-m-d'),
            'company_id'=>$company->id,
            'job_id'=> $job_role->id,
            'department_id'=> $department->id,
            'grade_id'=>$grade->id,
            'role_id'=>9,
            'payroll_type'=>'office'
        ]);
        $user->promotionHistories()->create([
            'old_grade_id' => $grade->id,'grade_id'=>$grade->id,'approved_on'=>date('Y-m-d'),'approved_by'=>$user->id
        ]);
        $user->grade_id=$grade->id;
        $user->save();
        //job role
        $user->jobs()->detach($job_role->id);

        $user->jobs()->attach($job_role->id, ['started' => date('Y-m-d')]);
        $user->job_id=$job_role->id;
        $user->save();
        $user->notify( new NewUserCreatedNotify($user));
        Auth::loginUsingId($user->id);
        // return redirect('home');
       return 'success';
    }

    public function save_payroll_policy(Request $request)
    {
        $company_id=companyId();
        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        $workflow=Workflow::first();

        if ($pp) {
            $pp->update(['basic_pay_percentage' => $request->basic_pay, 'payroll_runs' => 0, 'user_id' => Auth::user()->id, 'workflow_id' => $workflow->id, 'show_all_gross' => 1,'uses_approval' => 0]);
        } else {
            PayrollPolicy::create(['basic_pay_percentage' => $request->basic_pay, 'payroll_runs' => 0, 'user_id' => Auth::user()->id, 'workflow_id' => $workflow->id, 'company_id' => $company_id, 'show_all_gross' => 1,'uses_approval' => 0]);
        }

        $sc1 = SalaryComponent::create( ['name' =>"Transport",  'type' =>1, 'comment' => '', 'constant' => 'transport', 'formula' => 'gross_salary*'.($request->transport/100), 'company_id' => $company_id, 'taxable' => 1,'status'=>1]);
        $sc2 = SalaryComponent::create( ['name' =>"Housing",  'type' =>1, 'comment' =>'' , 'constant' => 'housing', 'formula' => 'gross_salary*'.($request->housing/100), 'company_id' => $company_id, 'taxable' => 1,'status'=>1]);
        $sc3 = SalaryComponent::create( ['name' =>"Pension Fund",  'type' =>0, 'comment' => '', 'constant' => 'pension_fund', 'formula' => '(basic_pay + housing_allowance + transport_allowance) * (0.08)', 'company_id' => $company_id, 'taxable' => 0,'status'=>1]);


            $rp=RegistrationProgress::where('company_id',companyId())->first();
            $rp->update(['has_payroll_policy'=>1,'completed'=>1]);

            $request->session()->flash('success', 'Leave Policy was saved successfully!');
            return redirect('home');
        }
        public function auto()
        {
    
            $company = Auth::user()->company;
            $old_user=Auth::user();
            //create grades
            for ($i = 0; $i < 5; $i++){
                Grade::create(['level' => 'Grade' . mt_rand(1, 10), 'leave_length' => mt_rand(14, 21), 'basic_pay' => mt_rand(50000, 400000), 'company_id' => $company->id]);
            }
            
            
            //create departments
            $company->departments()->createMany([
                [
                    'name' => 'Admin'
                ],
                [
                    'name' => 'Drivers'
                ],
                [
                    'name' => 'Section 1'
                ],
                [
                    'name' => 'Section 2'
                ],
            ]);

            //create branches
            $company->branches()->createMany([
    
                [
                    'name' => 'Branch 1'
                ],
                [
                    'name' => 'Branch 2'
                ],
                [
                    'name' => 'Branch 3'
                ],
                [
                    'name' => 'Branch 4'
                ],
            ]);
    //        create job roles
            $departments = \App\Department::where('company_id', $company->id)->get();
            foreach ($departments as $department) {
                for ($i = 0; $i <= 7; $i++) {
                    $faker = \Faker\Factory::create();
                    $jobname = $faker->jobTitle;
                    $job = \App\Job::create([
                        'title' => $jobname,
                        'description' => $jobname,
                        'department_id' => $department->id,
                        'personnel' => mt_rand(1, 4)
                    ]);
                }
            }
    //        create users
            $fakr = \Faker\Factory::create();
            $ids = [];
            $text = $fakr->text(5);
            for ($i = 0; $i < 10; $i++) {
                $ids[] = $text . mt_rand(111, 999);
            }
            // $users=["PNG/002","PNG/009","PNG/010","PNG/012","PNG/025","PNG/026","PNG/027","PNG/028","PNG/029","PNG/030","PNG/031","PNG/034","PNG/037","PNG/039","PNG/043","PNG/044","PNG/047","PNG/048","PNG/049","PNG/050","PNG/051","PNG/052","PNG/053","PNG/054","PNG/057","PNG/085","PNG/099","PNG/104","PNG/106","PNG/109","PNG/112","PNG/115","PNG/117","PNG/122","PNG/125","PNG/128","PNG/129","PNG/130","PNG/131","PNG/132","PNG/135","PNG/136","PNG/137","PNG/138","PNG/140","PNG/142","PNG/143","PNG/145","PNG/146","PNG/148","PNG/149","PNG/150","PNG/152","PNG/153","PNG/154","PNG/162","PNG/166","PNG/167","PNG/170","PNG/171","PNG/172","PNG/176","PNG/178","PNG/186","PNG/187","PNG/189","PNG/191","PNG/192","PNG/198","PNG/199","PNG/202","PNG/203","PNG/218","PNG/221","PNG/234","PNG/241","PNG/249","PNG/250","PNG/251","PNG/252","PNG/256","PNG/264","PNG/265","PNG/266","PNG/270","PNG/272","PNG/276","PNG/277","PNG/280","PNG/281","PNG/284","PNG/286","PNG/287","PNG/290","PNG/296","PNG/297","PNG/302","PNG/309","PNG/314","PNG/315","PNG/316","PNG/317","PNG/318","PNG/319","PNG/320","PNG/321","PNG/322","PNG/323","PNG/324","PNG/325","PNG/326","PNG/327","PNG/328","PNG/329","PNG/330","PNG/331","PNG/332","PNG/333","PNG/334","PNG/335","PNG/337","PNG/338","PNG/339","PNG/340","PNG/341","PNG/342","PNG/343","PNG/344","PNG/345","PNG/346","PNG/347","PNG/350","PNG/351","PNG/352","PNG/353","PNG/355","PNG/356","PNG/358","PNG/359","PNG/360","PNG/361","PNG/362","PNG/364","PNG/365","PNG/366","PNG/367","PNG/368","PNG/369","PNG/370","PNG/371","PNG/372","PNG/373","PNG/374","PNG/375","PNG/376","PNG/377","PNG/378","PNG/379","PNG/380","PNG/381","PNG/382","PNG/383","PNG/384","PNG/385","PNG/386","PNG/387","PNG/388","PNG/389","PNG/390","PNG/391","PNG/392","PNG/393","PNG/394","PNG/395","PNG/396","PNG/397","PNG/398","PNG/399","PNG/400","PNG/401","PNG/402","PNG/403","PNG/404","PNG/405","PNG/406","PNG/407","PNG/408","PNG/409","PNG/410","PNG/411","PNG/412","PNG/413","PNG/414","PNG/415","PNG/416","PNG/417","PNG/418","PNG/419","PNG/420","PNG/421","PNG/422","PNG/423","PNG/424","PNG/425","PNG/426","PNG/427","PNG/428","PNG/429","PNG/430","PNG/431","PNG/432","PNG/433","PNG/434","PNG/435","PNG/436","PNG/437","PNG/438","PNG/439","PNG/440","PNG/441","PNG/442","PNG/443","PNG/444","PNG/445","PNG/446","PNG/447","PNG/448","PNG/449","PNG/450","PNG/451","PNG/452","PNG/453","PNG/454","PNG/455","PNG/456","PNG/457","PNG/458","PNG/459","PNG/460","PNG/461","PNG/462","PNG/463","PNG/464","PNG/465","PNG/466","PNG/467","PNG/468","PNG/469","PNG/470","PNG/471","PNG/472","PNG/473"];
            $i = 1;
            foreach ($ids as $user) {
                $faker = \Faker\Factory::create();
                $gender = $faker->randomElements(['male', 'female']);
                $grade=Grade::where('company_id',$company->id)->inRandomOrder()
                ->first();
                $job=\App\Job::whereHas('department',function($query) use($company){
                    $query->where('departments.company_id',$company->id);
                })->inRandomOrder()->first();
                $approved_on=date('Y-m-d');
                $gc = explode(" ", $faker->name($gender));
                $new_user = \App\User::create([
                    'emp_num' => $user,
                    'first_name' => $gc[0],
                    'middle_name' => $gc[1],
                    'last_name' => end($gc),
                    'address' => $faker->streetAddress,
                    'company_id' => $company->id,
                    'email' => $faker->safeEmail,
                    'sex'=>substr($gender[0], 0, 1),
                    'dob' => $faker->date($format = 'Y-m-d', $max = '-21 years'),
                    'hiredate' => $faker->date($format = 'Y-m-d', $max = 'now'),
                    'role_id' => 4,
                    'job_id'=>$job->id,
                    'status' => 1]);
                    //assign line manager
                    $new_user->managers()->detach($old_user->id);
                    $new_user->managers()->attach($old_user->id);
                    $new_user->line_manager_id=$old_user->id;
                    $new_user->save();
                    //add grade
                    $new_user->promotionHistories()->create([
                        'old_grade_id' => $grade->id,'grade_id'=>$grade->id,'approved_on'=>$approved_on,'approved_by'=>$old_user->id
                    ]);
                    $new_user->grade_id=$grade->id;
                    $new_user->save();
                    //job role
                    $new_user->jobs()->detach($job->id);

                    $new_user->jobs()->attach($job->id, ['started' => date('Y-m-d',strtotime($approved_on))]);
                    $new_user->job_id=$job->id;
                    $new_user->save();
    
    
            }
    //    create leave policy
            $lp=LeavePolicy::where('company_id',$company->id)->first();
            $workflows=Workflow::all();
            if (!$lp) {
                $lp=LeavePolicy::create(['includes_weekend'=>0,'includes_holiday'=>0,'user_id'=>Auth::user()->id,'company_id'=>$company->id,'workflow_id'=>0]);
            }
    //        create payroll policy
            $pp = PayrollPolicy::create(['basic_pay_percentage' => 0, 'payroll_runs' => 1, 'user_id' => Auth::user()->id, 'company_id' => $company->id, 'show_all_gross' => 1]);
            $rp = RegistrationProgress::where('company_id', companyId())->first();
            if (!$rp) {
                $rp = RegistrationProgress::create(['has_users' => 0, 'has_grades' => 0, 'has_leave_policy' => 0, 'has_payroll_policy' => 0, 'has_departments' => 0, 'has_branches' => 0, 'has_job_roles' => 0, 'company_id' => companyId(), 'completed' => 0]);
            }
            $rp->update(['has_users' => 1, 'has_grades' => 1, 'has_leave_policy' => 1, 'has_payroll_policy' => 1, 'has_departments' => 1, 'has_branches' => 1, 'has_job_roles' => 1, 'company_id' => companyId(), 'completed' => 1]);
    
            }



}
