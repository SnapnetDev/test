<?php

namespace App\Http\Controllers;
use App\MedicalHistory;
use App\Repositories\QueryRepository;
use App\User;
use App\Company;
use App\Location;
use App\StaffCategory;
use App\Position;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Role;
use App\Qualification;
use App\UserGroup;
use App\Competency;
use App\Bank;
use App\Grade;
use Illuminate\Http\Request;
use App\Traits\UserProfile;

class UserProfileController extends Controller
{
	use UserProfile;
	public function index(Request $request, QueryRepository $query)
    {
    	 // $user=User::find($user_id);
      //  $locations=Location::all();
      //  $staff_categories=StaffCategory::all();
      //  $positions=Position::all();
      //  return view('empmgt.partials.details',['user'=>$user,'locations'=>$locations,'staff_categories'=>$staff_categories,'positions'=>$positions]);
//        return \Auth::user()->other;
        

        /*if(\Auth::user()->role->permissions->contains('constant', 'manage_user')){
            $user=\Auth::user();
        }elseif(\Auth::user()->user_temp){
            if (\Auth::user()->user_temp->last_change_approved==0){
                $user =\Auth::user()->user_temp;
            }else{
                $user=\Auth::user();
            }

        }elseif(!\Auth::user()->user_temp){
            $user=\Auth::user();
        }*/

       $user=\Auth::user();
       
       $user->id=\Auth::user()->id;
       $countries=\App\Country::all();
       $qualifications=Qualification::all();
       $competencies=Competency::all();
       $companies=Company::all();
       $banks=Bank::all();
       $grades=Grade::all();
       $queries=$query->getQueryData('data',1000000);
        $company=Company::find(session('company_id'));
        if(!$company){
          $company=Company::first();
        }
        $departments=$company->departments;
        $jobroles=$company->departments()->first()->jobs;
        $staff_categories=StaffCategory::all();
        $project_salary_categories=\App\PaceSalaryCategory::where('company_id',$company->id)->get();
        $medical_history = MedicalHistory::firstOrCreate(
            ['user_id' => Auth::id()],
            ['current_medical_conditions'=>[],
                'past_medical_conditions'=>[],
                'surgeries_hospitalizations'=>[],
                'medications'=>[],
                'medication_allergies'=>[],
                'family_history'=>[],
                'social_history'=>[],
                'others'=>[]]
        );
        $documents=\App\Document::where('id','<>',0)->where('user_id',Auth::id())->get();
       // return $user->skills()->where('skills.id',1)->first()->pivot->competency;
       return view('empmgt.profile',['queries'=>$queries,'user'=>$user,'qualifications'=>$qualifications,
           'countries'=>$countries,'competencies'=>$competencies,'companies'=>$companies,'banks'=>$banks,
           'company'=>$company,'grades'=>$grades,'departments'=>$departments,'jobs'=>$jobroles,
           'staff_categories'=>$staff_categories,'project_salary_categories'=>
               $project_salary_categories,'medical_history'=>$medical_history,'documents'=>$documents]);
    }

   public function store(Request $request)
    {
        //

        return $this->processPost($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        //
        return $this->processGet($id,$request);
    }

}
