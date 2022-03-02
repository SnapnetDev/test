<?php

namespace App\Traits;

use App\Bank;
use App\Dependant;
use App\Notifications\NewUserCreatedNotify;
use App\Qualification;
use App\RegistrationProgress;
use Illuminate\Http\Request;
use Auth;
use Excel;
use DB;
use App\User;
use App\Branch;
use App\Company;
use App\Department;
use App\Job;
use App\Grade;


trait ImportTrait {
	public $allowed=['xls','xlsx','csv'];
	public function processGet($route,Request $request){
		switch ($route) {
			case 'employees':
				# code...
				return $this->viewEmployeesImport($request);
				break;
			case 'departments':
				# code...
				return $this->viewDepartmentsImport($request);
				break;
			case 'branches':
				# code...
				return $this->viewBranchesImport($request);
				break;
			case 'jobroles':
				# code...
				return $this->viewJobrolesImport($request);
				break;
			case 'grades':
				# code...
				return $this->viewGradesImport($request);
				break;
				case 'update_dob':
                # code...
                return $this->viewUpdateDobImport($request);
                break;
            case 'download_employee_template':
                # code...
                return $this->downloadEmployeeUploadTemplate($request);
                break;
            case 'download_jobroles_template':
                # code...
                return $this->downloadJobRolesTemplate($request);
                break;
            case 'download_grades_template':
                # code...
                return $this->downloadGradeTemplate($request);
                break;
            case 'download_branches_template':
                # code...
                return $this->downloadBranchTemplate($request);
                break;
            case 'download_department_template':
                # code...
                return $this->downloadDepartmentTemplate($request);
                break;
            case 'download_academic_qualification_template':
                # code...
                return $this->downloadAcademicQualificationTemplate($request);
                break;
            case 'download_dependent_template':
                # code...
                return $this->downloadDependentTemplate($request);
                break;
            case 'download_work_experience_template':
                # code...
                return $this->downloadWorkExperienceTemplate($request);
                break;
            case 'download_reporting_line_template':
                    # code...
                    return $this->downloadReportingLineTemplate($request);
                    break;
                
			default:
				# code...
				break;
		}

	}


	public function processPost(Request $request){
		// try{
		switch ($request->type) {
			case 'employees':
				# code...
				return $this->importEmployees($request);
				break;
			case 'departments':
				# code...
				return $this->importDepartments($request);
				break;
			case 'branches':
				# code...
				return $this->importBranches($request);
				break;
			case 'jobroles':
				# code...
				return $this->importJobroles($request);
				break;
			case 'grades':
				# code...
				return $this->importGrades($request);
				break;
            case 'dependents':
                # code...
                return $this->importDependants($request);
                break;
            case 'update_dob':
                # code...
                return $this->importDob($request);
                break;
            case 'employee_supervisors':
                # code...
                return $this->importEmployeeSupervisor($request);
                break;
                


			default:
				# code...
				break;
		}
		// }
		// catch(\Exception $ex){
		// 	return response()->json(['status'=>'error','message'=>$ex->getMessage()]);
		// }
	}



	public function viewEmployeesImport(Request $request)
	{
		$companies=Company::all();
         return view('import.employees',compact('companies'));
	}
	public function viewDepartmentsImport(Request $request)
	{
		$companies=Company::all();
         return view('import.departments',compact('companies'));
	}
	public function viewBranchesImport(Request $request)
	{
		$companies=Company::all();
         return view('import.branches',compact('companies'));
	}
	public function viewJobrolesImport(Request $request)
	{
		$companies=Company::all();
         return view('import.jobroles',compact('companies'));
	}
	public function viewGradesImport(Request $request)
	{
		$companies=Company::all();
         return view('import.grades',compact('companies'));
	}
	 public function viewUpdateDobImport(Request $request)
    {
        $companies = Company::all();
        return view('import.update-dob', compact('companies'));
    }

	public function importEmpGrade(Request $request)
	{
		$document = $request->file('template');
		 //$document->getRealPath();
		// return $document->getClientOriginalName();
		// $document->getClientOriginalExtension();
		// $document->getSize();
		// $document->getMimeType();


                	$company=Company::find($request->company_id);

		 if($request->hasFile('template')){
            Excel::load($request->file('template')->getRealPath(), function ($reader) use ($company) {
            	$array=$reader->toArray();
                foreach ($reader->toArray() as $key => $row) {
                	$old_user=User::where('emp_num',$row['staff_id'])->first();
                	$grade=Grade::where('level',$row['grade'])->first();
				       if ($grade && $old_user) {

				       $hist=$old_user->promotionHistories()->first();

				       if ($hist) {
				       	$hist->update(['approved_by'=>Auth::user()->id,'approved_on'=>date('Y-m-d'),'old_grade_id'=>$grade->id,'grade_id'=>$grade->id]);
				       	$old_user->grade_id=$grade->id;
				       	$old_user->save();
				       }else{
				       	$old_user->promotionHistories()->create(['approved_by'=>Auth::user()->id,'approved_on'=>date('Y-m-d'),'old_grade_id'=>$grade->id,'grade_id'=>$grade->id]);
				       	$old_user->grade_id=$grade->id;
				       	$old_user->save();
				       }
				   }
				}
			});


          $request->session()->flash('success', 'Import was successful!');

        return back();
        }

	}

	public function importEmployees(Request $request)
	{
		$document = $request->file('template');

                	 $company=Company::find(companyId());

		 if($request->hasFile('template')){
             $datas = \Excel::load($request->file('template')->getrealPath(), function ($reader) {

             })->get();

             foreach($datas as $row){
                 $branch=Branch::where('name', 'like', '%'.$row['branch'].'%')->where('company_id',$company->id)->first();
                 $gender = ($row['gender'] == 'MALE') ? 'M' : ($row['gender'] == 'FEMALE' ? 'F' : '');
                 $confirmation_status = ($row['confirmation_status'] == 'N/A') ? '0' : ($row['confirmation_status'] == 'Confirmed' ? '1' : '2');
                 $company_id=$company->id;
                 $job=Job::where('title', 'like', '%'.$row['current_job_role'].'%')->whereHas('department',function($query) use($company_id){
                     $query->where('departments.company_id',$company_id);
                 })->first();
                  $grade=Grade::where('level',$row['grade'])->first();
                 $bank=Bank::where('bank_name', 'like', '%'.$row['bank'].'%')->first();
                 if(!$bank){
                     $bank=Bank::create(['bank_name'=>$row['bank']]);
                 }
                 $old_user=User::where('emp_num',$row['staffid'])->first();

                 if (!$old_user) {

                     $user=User::create(['first_name'=>$row['firstname'],'last_name'=>$row['lastname'],'email'=>$row['email'],'phone'=>$row['phone'],'emp_num'=>$row['staffid'],'sex'=>$gender,
                         'address'=>$row['address'],'marital_status'=>$row['marital_status'],'dob'=>$row['date_of_birth'],'branch_id'=>($branch ?$branch->id : 0),
                         'grade_id'=>($grade ?$grade->id : 0),
                         'bank_id'=>($bank ?$bank->id : 0),'job_id'=>($job ?$job->id : 0),'status'=>$confirmation_status,'bank_account_no'=>$row['account_number'],
                         'company_id'=>$company->id,'hiredate'=>$row['hiredate'],'confirmation_date'=>$row['date_confirmed'],'role_id'=>4]);
                    //  $user->notify(new NewUserCreatedNotify($user));
                     $user_temp=\App\UserTemp::updateOrCreate(['user_id'=>$user->id],$user->toArray());
                     if ($row['next_of_kin']!='' && $row['relationship']!='') {

                         $nok=\App\Nok::create(['name'=>$row['next_of_kin'],'phone'=>$row['phone_of_next_of_kin'],'address'=>$row['address_of_next_of_kin'],'relationship'=>strtolower($row['relationship']),'user_id'=>$user->id]);
                     }

                     if ($grade) {

                         $user->promotionHistories()->create(['approved_by'=>Auth::user()->id,'approved_on'=>date('Y-m-d'),'old_grade_id'=>$grade->id,'grade_id'=>$grade->id]);
                     }
                     if($job){
                         $user->jobs()->attach($job->id,['started'=>date('Y-m-d')]);
                     }
                 }else{
                     $old_user->update(['first_name'=>$row['firstname'],'last_name'=>$row['lastname'],'email'=>$row['email'],'phone'=>$row['phone'],'sex'=>$gender,
                         'address'=>$row['address'],'marital_status'=>$row['marital_status'],'dob'=>$row['date_of_birth'],'branch_id'=>($branch ?$branch->id : 0),

                         'bank_id'=>($bank ?$bank->id : 0),'status'=>$confirmation_status,'bank_account_no'=>$row['account_number'],
                         'company_id'=>$company->id,'hiredate'=>$row['hiredate'],'confirmation_date'=>$row['date_confirmed']]);


                     if ($row['next_of_kin']!='' && $row['relationship']!='') {

                         \App\Nok::updateOrCreate(
                             ['user_id' => $old_user->id],
                             ['name'=>$row['next_of_kin'],'phone'=>$row['phone_of_next_of_kin'],'address'=>$row['address_of_next_of_kin'],'relationship'=>strtolower($row['relationship']),'user_id'=>$old_user->id]
                         );


                     }

                 }


             }


//          $request->session()->flash('success', 'Import was successful!');
             if($request->source=='onboarding'){
                $this->update_reg_progress('has_users');

                 return back()->with(['success'=>'Employees Import was successful']);
             }
        return 'success';
        }else{
             return back()->with(['error'=>'Employees could not be imported']);
         }


	}
	public function importDepartments(Request $request)
	{
		$document = $request->file('template');
		$company=Company::find(companyId());
		 if($request->hasFile('template')){
             $datas = \Excel::load($request->file('template')->getrealPath(), function ($reader) {

             })->get();

             foreach($datas as $row){
                 $dept=Department::where(['name'=>$row['name'],'company_id'=>companyId()])->first();
                 if (!$dept) {
                     Department::create(['name'=>$row['name'],'company_id'=>$company->id]);
                 }
             }

             if($request->source=='onboarding'){
                 $this->update_reg_progress('has_departments');

                 return back()->with(['success'=>'Department Import was successful']);
             }
            return 'success';
//          $request->session()->flash('success', 'Import was successful!');

//        return back();
        }else{
             return back()->with(['error'=>'Departments could not be imported']);
         }


	}

	public function importBranches(Request $request)
	{
		$document = $request->file('template');
		$company=Company::find(companyId());


		 if($request->hasFile('template')){
             $datas = \Excel::load($request->file('template')->getrealPath(), function ($reader) {
             })->get();

             foreach($datas as $row){
                 $branch=Branch::where(['name'=>$row['branch_name'],'company_id'=>companyId()])->first();
                 if (!$branch) {
                     Branch::create(['name'=>$row['branch_name'],'company_id'=>$company->id,'address'=>$row['branch_address'],'email'=>$row['branch_email']]);
                 }
             }
             if($request->source=='onboarding'){
                 $this->update_reg_progress('has_branches');

                 return back()->with(['success'=>'Branch Import was successful']);
             }

            return 'success';
//          $request->session()->flash('success', 'Import was successful!');

//        return back();
        }else{
             return back()->with(['error'=>'Branches could not be imported']);
         }


	}

	public function importJobroles(Request $request)
	{
		$document = $request->file('template');
		$company=Company::find($request->company_id);


		 if($request->hasFile('template')){
             $datas = \Excel::load($request->file('template')->getrealPath(), function ($reader) {

             })->get();

             foreach($datas as $row){
            		 $department=Department::where(['name'=>$row['department'],'company_id'=>companyId()])->first();
            		if ($department) {
            			$job=Job::where(['title'=>$row['title'],'department_id'=>$department->id])->first();
            			if (!$job) {
            				 $qualification=\App\Qualification::where(['name'=>$row['qualification']])->first();
                             if(!$qualification){
                                $qualification=\App\Qualification::create([ 'name' => $row['qualification']]);
                             }
                             \Log::info($department);
                             \Log::info($qualification);
            				Job::create(['title'=>$row['title'],'department_id'=>$department->id,'description'=>$row['description'],'qualification_id'=>$qualification->id]);
            			}elseif($job){
            					$job->update(['description'=>$row['description']]);
            				}

            		}

						 }
             if($request->source=='onboarding'){
                 $this->update_reg_progress('has_job_roles');
                 return back()->with(['success'=>'Job Roles Import was successful']);
             }

             return 'success';

//          $request->session()->flash('success', 'Import was successful!');
//
//        return back();
        }else{
             return back()->with(['error'=>'Job Roles could not be imported']);
         }

	}

	public function importUserRoles(Request $request)
	{
		$document = $request->file('template');
		$company=Company::find($request->company_id);
		 //$document->getRealPath();
		// return $document->getClientOriginalName();
		// $document->getClientOriginalExtension();
		// $document->getSize();
		// $document->getMimeType();


		 if($request->hasFile('template')){
            Excel::load($request->file('template')->getRealPath(), function ($reader) use($company) {

            	foreach ($reader->toArray() as $key => $row) {
            		 // $hiredate=\Carbon\Carbon::createFromFormat('d/m/Y', $row['hiredate'])->toDateTimeString();
            		if ($row['jobid']) {
            			$job=\App\Job::find($row['jobid']);
            			$user=\App\User::where(['emp_num'=>$row['staff_id']])->first();
            			$user->jobs()->attach($job->id);
            			$user->job_id=$job->id;
            			$user->department_id=$job->department->id;
            			$user->save();
            		}


						 }
            });

          $request->session()->flash('success', 'Import was successful!');

        return back();
        }

	}

	public function importGrades(Request $request)
	{

		// $document = $request->file('template');
		$company=Company::find(companyId());
		 if($request->hasFile('template')){
             $datas = \Excel::load($request->file('template')->getrealPath(), function ($reader) {
             })->get();

             foreach($datas as $row){
                 $grade=Grade::where('level',$row['level'])->where('company_id',$company->id)->first();
                 if (!$grade) {
                     Grade::create(['level'=>$row['level'],'leave_length'=>$row['leave_length'],'basic_pay'=>$row['monthly_gross'],'company_id'=>$company->id,'description'=>$row['description']]);
                 }
             }
            if($request->source=='onboarding'){
                $this->update_reg_progress('has_grades');
                return back()->with(['success'=>'Grade Import was successful']);
            }
             return 'success';

        }else{
             return back()->with(['error'=>'Grade could not be imported']);
         }

	}
    public function importDependants(Request $request)
    {
        // $document = $request->file('template');
        $company=Company::find(companyId());
        if($request->hasFile('template')){
             $datas = \Excel::load($request->file('template')->getrealPath(), function ($reader) {
            })->get();

            foreach($datas[0] as $row){
                $dependant=Dependant::where('name',$row['name'])->where('empnum',$row['employee_id'])->first();
                if (!$dependant) {
                    Dependant::create(['name'=>$row['name'],'user_id'=>$row['employee_id'],'email'=>$row['email'],'phone'=>$row['phone'],
                        'dob'=>$row['date_of_birth'],'relationship'=>$row['relationship'],'company_id'=>$company->id]);
                }
            }

            return 'success';

        }

    }
    public function importEmployeeSupervisor(Request $request)
    {
        $document = $request->file('template');
        $company = Company::find(companyId());
        //$document->getRealPath();
        // return $document->getClientOriginalName();
        // $document->getClientOriginalExtension();
        // $document->getSize();
        // $document->getMimeType();


        if ($request->hasFile('template')) {
            \Excel::load($request->file('template')->getRealPath(), function ($reader) use ($company) {

                foreach ($reader->toArray() as $key => $row) {

                    if ($row['staff_id']) {
                        // $dob = \Carbon\Carbon::createFromFormat('Y-m-d',$row['dob'])->format('Y-m-d');
                        // $hiredate = \Carbon\Carbon::createFromFormat('Y-m-d', $row['hiredate'])->format('Y-m-d');
                        $user = \App\User::where(['emp_num' => $row['staff_id']])->first();
                        if($user){
                            $supervisor=\App\User::where(['emp_num' => $row['supervisor_staff_id']])->first();
                            if($user){
                                try{
                                
                                $has_manager = $user->managers->contains('id', $supervisor->id);
                                if (!$has_manager && $supervisor->id != $user->id) {
                                    $user->line_manager_id = $supervisor->id;
                                    $user->save();
                                    $user->managers()->attach($supervisor->id);
                                    
                                }
                                }catch(\Exception $e){
                                    return 'error';
                                }
                            }
                        }


                    }
                }
            });

            $request->session()->flash('success', 'Import was successful!');

            return 'success';
        }
    }
	public function importDob(Request $request)
    {
        $document = $request->file('template');
        $company = Company::find($request->company_id);
        //$document->getRealPath();
        // return $document->getClientOriginalName();
        // $document->getClientOriginalExtension();
        // $document->getSize();
        // $document->getMimeType();


        if ($request->hasFile('template')) {
            Excel::load($request->file('template')->getRealPath(), function ($reader) use ($company) {

                foreach ($reader->toArray() as $key => $row) {

                    if ($row['staff_id']) {
                        // $dob = \Carbon\Carbon::createFromFormat('Y-m-d',$row['dob'])->format('Y-m-d');
                        // $hiredate = \Carbon\Carbon::createFromFormat('Y-m-d', $row['hiredate'])->format('Y-m-d');
                        $user = \App\User::where(['emp_num' => $row['staff_id']])->first();
                        if($user){
                            try{
                            $user->dob = $row['email'];
                            // $user->hiredate = $row['hiredate'];
                            $user->save();
                            }catch(\Exception $e){
                                return 'error';
                            }
                        }


                    }
                }
            });

            $request->session()->flash('success', 'Import was successful!');

            return 'success';
        }
    }

    public function downloadEmployeeUploadTemplate(Request $request){
        $first_row = [
            ['StaffID'=>'PN12345','FirstName'=>'John','MiddleName'=>'Robert', 'LastName'=>'Doe','Email'=>'','Gender'=>''
                ,'Date of Birth'=>'2000-01-01','Phone'=>'081234567890','Marital Status'=>'','Address'=>'','Hiredate'=>'2019-01-01','Confirmation Status'=>''
                ,'Date confirmed'=>'2019-06-01','Branch'=>'','Current Job Role'=>'','Grade'=>'','Bank'=>''
                ,'Account Number'=>'','Next of Kin'=>'Jane Doe','Relationship'=>'','Phone of Next of Kin'=>'081234567890','Address of Next of Kin'=>'']
        ];
        $company_id=companyId();
        $branches = Branch::where('company_id',companyId())->select('company_id','name')->get()->toArray();
        $job_roles = Job::select('id','title')->whereHas('department',function($query) use($company_id){
            $query->where('departments.company_id',$company_id);
        })->get()->toArray();
        $banks = Bank::select('id','bank_name as name')->get()->toArray();
        $grades = Grade::select('id','level')->get()->toArray();

        $marital_status = [['option' => 'single', 'value' => 'Single'], ['option' => 'married', 'value' => 'Married'], ['option' => 'divorced', 'value' => 'Divorced'],['option' => 'separated', 'value' => 'Separated']];
        $gender=[['option' => 'M', 'value' => 'Male'], ['option' => 'F', 'value' => 'Female']];
        $confirmation_status=[['option' => '1', 'value' => 'Confirmed'], ['option' => '0', 'value' => 'Probation']];
        $relationship = [['option' => 'spouse', 'value' => 'spouse'], ['option' => 'mother', 'value' => 'mother'], ['option' => 'father', 'value' => 'father'],['option' => 'son', 'value' => 'son']
            ,['option' => 'daughter', 'value' => 'daughter'],['option' => 'brother', 'value' => 'brother'],['option' => 'sister', 'value' => 'sister'],['option' => 'nephew', 'value' => 'nephew'],['option' => 'niece', 'value' => 'niece']
            ,['option' => 'uncle', 'value' => 'uncle'],['option' => 'aunt', 'value' => 'aunt'],['option' => 'friend', 'value' => 'friend']];
        return $this->exportToExcelDropDown('Employee Upload Template',
            ['Employee Details' => [$first_row, ''],'relationship' => [$relationship, 'S'],'gender' => [$gender, 'E'],'marital_status' => [$marital_status, 'H'],'confirmation_status' => [$confirmation_status, 'K'],'job_roles' => [$job_roles, 'N'],'banks' => [$banks, 'P'],'grades' => [$grades, 'O'],'branches' => [$branches, 'M', 'branches']]
        );
    }
    public function downloadJobRolesTemplate(Request $request){
        $first_row = [
            ['Title'=>'','Department'=>'', 'Qualification'=>'','Description'=>'']
        ];

        $departments = Department::select('id','name')->where('company_id',companyId())->get()->toArray();
        $qualifications = Qualification::select('id','name')->get()->toArray();

                return $this->exportToExcelDropDown('Job Roles Template',
            ['Job Roles' => [$first_row, ''],'departments' => [$departments, 'B'],'qualifications' => [$qualifications, 'C','qualifications']]
        );
    }
    public function downloadAcademicQualificationTemplate(Request $request){
        $first_row = [
            ['Employee ID'=>'','Title'=>'', 'Qualification'=>'','Year'=>'','Institution'=>'','Class'=>'','Discipline'=>'']
        ];
        $users = User::where('company_id',companyId())->where('status','!=','2')->select('name','emp_num','company_id')->get();
        $users=$users->map(function ($name) {
            return ['name'=>$name->name,'emp_num'=>$name->emp_num];
        });

        $qualifications = Qualification::select('id','name')->get()->toArray();

        return $this->exportToExcelDropDown('Employee Academic Qualification Template',
            ['Job Roles' => [$first_row, ''],'users' => [$users, 'A'],'qualifications' => [$qualifications, 'C','qualifications']]
        );
    }
    public function downloadWorkExperienceTemplate(Request $request){
        $first_row = [
            ['Employee ID'=>'','Organization'=>'', 'Position'=>'','StartDate'=>'','EndDate'=>'']
        ];
        $users = User::where('company_id',companyId())->where('status','!=','2')->select('name','emp_num','company_id')->get();
        $users=$users->map(function ($name) {
            return ['name'=>$name->name,'emp_num'=>$name->emp_num];
        });



        return $this->exportToExcelDropDown('Work Experience Template',
            ['Work Experience' => [$first_row, ''],'users' => [$users, 'A', 'users']]
        );
    }
    public function downloadDependentTemplate(Request $request){
        $first_row = [
            ['Employee ID'=>'','Name'=>'', 'Date of Birth'=>'','Email'=>'','Phone'=>'','Relationship'=>'']
        ];
        $users = User::where('company_id',companyId())->where('status','!=','2')->select('name','emp_num','company_id')->get();
        $users=$users->map(function ($name) {
            return ['name'=>$name->name,'emp_num'=>$name->emp_num];
        });
        $relationship = [['option' => 'spouse', 'value' => 'spouse'], ['option' => 'mother', 'value' => 'mother'], ['option' => 'father', 'value' => 'father'],['option' => 'son', 'value' => 'son']
            ,['option' => 'daughter', 'value' => 'daughter'],['option' => 'brother', 'value' => 'brother'],['option' => 'sister', 'value' => 'sister'],['option' => 'nephew', 'value' => 'nephew'],['option' => 'niece', 'value' => 'niece']
            ,['option' => 'uncle', 'value' => 'uncle'],['option' => 'aunt', 'value' => 'aunt'],['option' => 'friend', 'value' => 'friend']];

        return $this->exportToExcelDropDown('Dependent Template',
            ['Employee Dependant' => [$first_row, ''],'relationship'=>[$relationship,'F'],'users' => [$users, 'A', 'users']]
        );
    }
    public function downloadGradeTemplate(Request $request){
        $first_row = [
            ['Level'=>'','Leave Length'=>'', 'Monthly Gross'=>'','Description'=>'']
        ];


        return $this->exportToExcelDropDown('Grades Template',
            ['Grades' => [$first_row, '']]
        );
    }
    public function downloadReportingLineTemplate(Request $request){
        $first_row = [
            ['Staff Id'=>'','Staff Name'=>'', 'Staff Supervisor Name'=>'','Supervisor Staff Id'=>'']
        ];


        return $this->exportToExcelDropDown('Reporting Line Template',
            ['Reporting Line' => [$first_row, '']]
        );
    }


    public function downloadBranchTemplate(Request $request){

        $first_row = [
            ['Branch Name'=>'','Branch Address'=>'','Branch Email'=>'']
        ];


        return $this->exportToExcelDropDown('Branch Template',
            ['Branches' => [$first_row, '']]
        );
    }
    public function downloadDepartmentTemplate(Request $request){
        $first_row = [
            ['Name'=>'']
        ];


        return $this->exportToExcelDropDown('Department Template',
            ['Departments' => [$first_row, '']]
        );
    }

    private function exportToExcelDropDown($worksheetname, $data)
    {

        return \Excel::create($worksheetname, function ($excel) use ($data) {

            foreach ($data as $sheetname => $realdata) {
                $excel->sheet($sheetname, function ($sheet) use ($realdata, $sheetname, $data) {
                    $last = collect($data)->last();
                    $sheet->fromArray($realdata[0]);
                    if(count($data)>1){
                    if ($sheetname == $last[2]) {

                        $i = 1;
                        foreach ($data as $key => $data) {
                            $Cell = $data[1];
                            if ($data[1] != '') {

                                $sheet->_parent->addNamedRange(
                                    new \PHPExcel_NamedRange(
                                        "sd{$data[1]}", $sheet->_parent->getSheet($i), "B2:B" . $sheet->_parent->getSheet($i)->getHighestRow()
                                    )
                                );
                                $i++;
                                for ($j = 2; $j <= 500; $j++) {

                                    $objValidation = $sheet->_parent->getSheet(0)->getCell("{$data[1]}$j")->getDataValidation();
                                    $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                                    $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                                    $objValidation->setAllowBlank(false);
                                    $objValidation->setShowInputMessage(true);
                                    $objValidation->setShowErrorMessage(true);
                                    $objValidation->setShowDropDown(true);
                                    $objValidation->setErrorTitle('Input error');
                                    $objValidation->setError('Value is not in list.');
                                    $objValidation->setPromptTitle('Pick from list');
                                    // $objValidation->setPrompt('Please pick a value from the drop-down list.');
                                    $objValidation->setFormula1("sd{$data[1]}");

                                }
                            }
                        }
                    }
                    }
                });

            }
        })->download('xlsx');
    }

    public function update_reg_progress($column)
    {
        $rp=RegistrationProgress::where('company_id',companyId())->first();
        if (!$rp){
            $rp=RegistrationProgress::create(['has_users'=>0,'has_grades'=>0,'has_leave_policy'=>0,'has_payroll_policy'=>0,'has_departments'=>0,'has_branches'=>0, 'has_job_roles'=>0,'company_id'=>companyId(),'completed'=>0]);
        }
        $rp->update([$column=>1]);
    }



}
