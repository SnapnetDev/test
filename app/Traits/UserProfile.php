<?php
namespace App\Traits;
use App\Nok;
use App\NokTemp;
use App\Notifications\ApproveDependantChange;
use App\Notifications\ApproveEducationalHistoryChange;
use App\Notifications\ApproveEmploymentHistoryChange;
use App\User;
use App\Qualification;
use App\EducationHistory;
use App\EmploymentHistory;
use App\Skill;
use App\Dependant;
use App\Department;
use App\Company;
use App\Job;
use App\UserTemp;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Validator;
use Illuminate\Support\Facades\Hash;

trait UserProfile{
    public function processGet($route,Request $request)
    {
        switch ($route) {
            case 'profile':
                return $this->profile($request);
                break;
            case 'academic_history':
                return $this->academic_history($request);
                break;
            case 'delete_academic_history':
                return $this->delete_academic_history($request);
                break;
            case 'dependant':
                return $this->dependant($request);
                break;
            case 'delete_dependant':
                return $this->delete_dependant($request);
                break;
            case 'skill':
                return $this->skill($request);
                break;
            case 'delete_skill':
                return $this->delete_skill($request);
                break;
            case 'work_experience':
                return $this->work_experience($request);
                break;
            case 'delete_work_experience':
                return $this->delete_work_experience($request);
                break;
            case 'states':
                return $this->states($request);
                break;
            case 'lgas':
                return $this->lgas($request);
                break;
            case 'changegrade':
                return $this->changegrade($request);
                break;
            case 'primary_manager':
                return $this->makePrimaryManager($request);
                break;
            case 'remove_manager':
                return $this->removeManager($request);
                break;

            case 'delete_job_history':
                return $this->delete_job_history($request);
                break;
            case 'notifications':
                return $this->viewNotifications($request);
                break;
            case 'notification':
                return $this->viewNotificationInfo($request);
                break;
            case 'separation_types':
                return $this->getSeparationTypes($request);
                break;
            case 'clear_notifications':
                return $this->clearNotifications($request);
                break;
            case 'clear_notification':
                return $this->clearNotification($request);
                break;
            case 'reject_profile_changes':
                return $this->reject_changes($request);
                break;
            case 'change_approval':
                return $this->change_approval($request);
                break;
            case 'view_profile_change':
                return $this->view_profile_change($request);
                break;
            case 'reject_nok_changes':
                return $this->reject_nok_changes($request);
                break;
            case 'nok_change_approval':
                return $this->nok_change_approval($request);
                break;
            case 'view_nok_change':
                return $this->view_nok_change($request);
                break;
            case 'reject_educational_history_changes':
                return $this->reject_educational_history_changes($request);
                break;
            case 'educational_history_change_approval':
                return $this->educational_history_change_approval($request);
                break;
            case 'view_educational_history_change':
                return $this->view_educational_history_change($request);
                break;
            case 'reject_employment_history_changes':
                return $this->reject_employment_history_changes($request);
                break;
            case 'employment_history_change_approval':
                return $this->employment_history_change_approval($request);
                break;
            case 'view_employment_history_change':
                return $this->view_employment_history_change($request);
                break;
            case 'reject_dependant_changes':
                return $this->reject_dependant_changes($request);
                break;
            case 'dependant_change_approval':
                return $this->dependant_change_approval($request);
                break;
            case 'view_dependant_change':
                return $this->view_dependant_change($request);
                break;



            default:
                # code...
                break;
        }
    }
    public function processPost(Request $request)
    {
        switch ($request->type) {
            case 'academic_history':
                return $this->save_academic_history($request);
                break;
            case 'dependant':
                return $this->save_dependant($request);
                break;
            case 'skill':
                return $this->save_skill($request);
                break;
            case 'work_experience':
                return $this->save_work_experience($request);
                break;
            case 'job_history':
                return $this->save_job_history($request);
                break;
            case 'change_password':
                return $this->changePassword($request);
                break;
            case 'save_separation':
                return $this->save_separation($request);
                break;
            case 'approve_profile_changes':
                return $this->approve_changes($request);
                break;
            case 'approve_nok_changes':
                return $this->approve_nok_changes($request);
                break;
            case 'approve_educational_history_changes':
                return $this->approve_educational_history_changes($request);
                break;
            case 'approve_employment_history_changes':
                return $this->approve_employment_history_changes($request);
                break;
            case 'approve_dependant_changes':
                return $this->approve_dependant_changes($request);
                break;


            default:
                # code...
                break;
        }
    }

    public function profile(Request $request)
    {
        $user=User::find($request->user_id);
        // return view('empmgt.partials.details',['user'=>$user]);
        return $request->user_id;
    }
    public function academic_history(Request $request)
    {
        $ah=EducationHistory::find($request->academic_history_id);
        return $ah;
    }
    public function delete_academic_history(Request $request)
    {
        $ah=EducationHistory::find($request->academic_history_id);
        if ($ah) {
            $ah->delete();
            return 'success';
        }
        return 'failed';
    }
    public function save_academic_history(Request $request)
    {
        try{
            if(\Auth::user()->role->permissions->contains('constant', 'manage_user')) {
                $ah = EducationHistory::updateOrCreate(['id' => $request->academic_history_id], ['title' => $request->title,'qualification_id' => $request->qualification_id, 'institution' => $request->institution, 'year' => $request->year, 'course' => $request->course, 'grade' => $request->grade, 'emp_id' => $request->user_id,'company_id'=>companyId(),'last_change_approved'=>1,'last_change_approved_by'=>\Auth::user()->id,'last_change_approved_on'=>date('Y-m-d H:i:s')]);
                return 'success';
            }else{
                if (companyId()==8){
                    $hrs=User::whereHas('role.permissions',function ($query){
                        $query->where('constant','manage_user');
                    })->where('company_id',8)->get();
                }
                elseif (companyId()==9){
                    $hrs=User::whereHas('role.permissions',function ($query){
                        $query->where('constant','manage_user');
                    })->where('company_id',9)->get();
                }else{
                    $hrs=User::whereHas('role.permissions',function ($query){
                        $query->where('constant','manage_user');
                    })->where('company_id','!=',8)->where('company_id','!=',9)->get();
                }
                $user=User::find($request->user_id);
                foreach ($hrs as $hr){
                    $hr->notify( new ApproveEducationalHistoryChange($user));
                }
                $ah = EducationHistory::updateOrCreate(['id'=>$request->academic_history_id],['title'=>$request->title,'qualification_id'=>$request->qualification_id,'institution'=>$request->institution,'year'=>$request->year,'course'=>$request->course,'grade'=>$request->grade,'emp_id'=>$request->user_id,'last_change_approved'=>0,'company_id'=>companyId()]);
            }



        }catch(\Exception $ex){
            return $ex->getMessage();
        }


    }
    public function dependant(Request $request)
    {
        $dependant=Dependant::find($request->dependant_id);
        return $dependant;
    }
    public function delete_dependant(Request $request)
    {
        $dependant=Dependant::find($request->dependant_id);
        if ($dependant) {
            $dependant->delete();
            return 'success';
        }
        return 'failed';
    }
    public function save_dependant(Request $request)
    {
        try{
            if(\Auth::user()->role->permissions->contains('constant', 'manage_user')) {
                $dependant = Dependant::updateOrCreate(['id' => $request->dependant_id], ['name' => $request->name, 'dob' => date('Y-m-d', strtotime($request->dob)), 'email' => $request->email, 'phone' => $request->phone, 'relationship' => $request->relationship, 'user_id' => $request->user_id,'last_change_approved'=>1,'company_id'=>companyId(),'last_change_approved_by' => \Auth::user()->id, 'last_change_approved_on' => date('Y-m-d H:i:s')]);
                return 'success';
            }else{
                    if (companyId()==8){
                        $hrs=User::whereHas('role.permissions',function ($query){
                            $query->where('constant','manage_user');
                        })->where('company_id',8)->get();
                    }
                elseif (companyId()==9){
                    $hrs=User::whereHas('role.permissions',function ($query){
                        $query->where('constant','manage_user');
                    })->where('company_id',9)->get();
                }else{
                        $hrs=User::whereHas('role.permissions',function ($query){
                            $query->where('constant','manage_user');
                        })->where('company_id','!=',8)->where('company_id','!=',9)->get();
                    }
                    $user=User::find($request->user_id);
                    foreach ($hrs as $hr){
                        $hr->notify( new ApproveDependantChange($user));
                    }
                    $dependant = Dependant::updateOrCreate(['id'=>$request->dependant_id],['name'=>$request->name,'dob'=>date('Y-m-d',strtotime($request->dob)),'email'=>$request->email,'phone'=>$request->phone,'relationship'=>$request->relationship,'user_id'=>$request->user_id,'last_change_approved'=>0,'company_id'=>companyId()]);
                    return 'success';
            }


        }catch(\Exception $ex){
            return $ex->getMessage();
        }
    }
    public function skill(Request $request)
    {
        $user=User::find($request->user_id);
        $skill=$user->skills()->where('skills.id',$request->skill_id)->first();
        return $skill;
    }
    public function delete_skill(Request $request)
    {
        $user=User::find($request->user_id);


        $user->skills()->detach($request->skill_id);
        return 'success';
    }
    public function save_skill(Request $request)
    {
        try{
            $skill=Skill::where('id',$request->skill)->orWhere('name','like','%'.$request->skill.'%')->first();
            if (!$skill) {
                $skill=Skill::create(['name'=>$request->skill]);
            }
            $user=User::find($request->user_id);
            $has_skill = User::whereHas('skills', function ($query) use($skill){
                $query->where('skills.id', $skill->id);
            })->get();
            if ($has_skill) {
                $user->skills()->detach($skill->id);
            }

            $user->skills()->attach($skill->id, ['competency_id' => $request->competency_id]);
            return 'success';

        }catch(\Exception $ex){
            return $ex->getMessage();
        }
    }
    public function work_experience(Request $request)
    {
        $work_experience=EmploymentHistory::find($request->work_experience_id);
        return $work_experience;
    }
    public function delete_work_experience(Request $request)
    {
        $work_experience=EmploymentHistory::find($request->work_experience_id);
        if ($work_experience) {
            $work_experience->delete();
            return 'success';
        }
        return 'failed';
    }
    public function save_work_experience(Request $request)
    {
        try{

            if(\Auth::user()->role->permissions->contains('constant', 'manage_user')) {
                $work_experience = EmploymentHistory::updateOrCreate(['id' => $request->work_experience_id], ['organization' => $request->organization, 'position' => $request->position, 'start_date' => date('Y-m-d', strtotime($request->start_date)), 'end_date' => date('Y-m-d', strtotime($request->end_date)), 'user_id' => $request->user_id, 'company_id' => companyId(), 'last_change_approved' => 1, 'last_change_approved_by' => \Auth::user()->id, 'last_change_approved_on' => date('Y-m-d H:i:s')]);
                return 'success';
            }else{
                if (companyId()==8){
                    $hrs=User::whereHas('role.permissions',function ($query){
                        $query->where('constant','manage_user');
                    })->where('company_id',8)->get();
                }
                elseif (companyId()==9){
                    $hrs=User::whereHas('role.permissions',function ($query){
                        $query->where('constant','manage_user');
                    })->where('company_id',9)->get();
                }else{
                    $hrs=User::whereHas('role.permissions',function ($query){
                        $query->where('constant','manage_user');
                    })->where('company_id','!=',8)->where('company_id','!=',9)->get();
                }
                $user=User::find($request->user_id);

                $work_experience = EmploymentHistory::updateOrCreate(['id' => $request->work_experience_id], ['organization' => $request->organization, 'position' => $request->position, 'start_date' => date('Y-m-d', strtotime($request->start_date)), 'end_date' => date('Y-m-d', strtotime($request->end_date)), 'user_id' => $request->user_id, 'company_id' => companyId(), 'last_change_approved' => 0]);
                foreach ($hrs as $hr){
                    $hr->notify( new ApproveEmploymentHistoryChange($user));
                }
            }


        }catch(\Exception $ex){
            return $ex->getMessage();
        }
    }
    public function save_job_history(Request $request)
    {
        try{
            $user=User::find($request->user_id);

            $user->jobs()->detach($request->job_id);

            $user->jobs()->attach($request->job_id, ['started' => date('Y-m-d',strtotime($request->started)),'ended'=>date('Y-m-d',strtotime($request->ended))]);
            $user->job_id=$request->job_id;
            $user->save();

            return 'success';

        }catch(\Exception $ex){
            return $ex->getMessage();
        }
    }
    public function update_job_history(Request $request)
    {
        try{
            $user=User::find($request->user_id);

            $user->jobs()->detach($request->job_id);

            $user->jobs()->updateExistingPivot($request->job_id, ['started' => date('Y-m-d',strtotime($request->started)),'ended'=>date('Y-m-d',strtotime($request->ended))]);

            return 'success';

        }catch(\Exception $ex){
            return $ex->getMessage();
        }
    }
    public function delete_job_history(Request $request)
    {
        try{
            $user=User::find($request->user_id);
            ;
            $user->jobs()->detach($request->job_id);

            $job=$user->jobs()->latest()->first();
            // dd($manager);
            if ($job) {
                $user->job_id=$job->id;
                $user->department_id=$job->department->id;
            }else{
                $user->job_id=0;
                $user->department_id=0;
            }
            $user->save();

            return 'success';

        }catch(\Exception $ex){
            return $ex->getMessage();
        }
    }

    public function states(Request $request)
    {
        $country=\App\Country::find($request->country_id);
        return $country->states;
    }
    public function lgas(Request $request)
    {

        $state=\App\State::find($request->state_id);
        return $state->lgas;
    }

    public function changegrade(Request $request)
    {
        $user=User::find($request->user_id);
        $approved_on=$request->filled('effective_date')?date('Y-m-d',strtotime($request->effective_date)):date('Y-m-d');
        if($user->promotionHistories()->count()>0){
            $oldgrade=$user->promotionHistories()->latest()->first()->grade;
            if ($oldgrade->id!=$request->grade_id) {
                $user->promotionHistories()->create([
                    'old_grade_id' => $oldgrade->id,'grade_id'=>$request->grade_id,'approved_on'=>$approved_on,'approved_by'=>Auth::user()->id
                ]);
                $user->grade_id=$request->grade_id;
                $user->save();
            }

        }else{
            $user->promotionHistories()->create([
                'old_grade_id' => $request->grade_id,'grade_id'=>$request->grade_id,'approved_on'=>$approved_on,'approved_by'=>Auth::user()->id
            ]);
            $user->grade_id=$request->grade_id;
            $user->save();
        }

        return 'success';
    }

    public function removeManager(Request $request)
    {
        $user=User::find($request->user_id);


        $user->managers()->detach($request->manager_id);
        $manager=$user->managers()->latest()->first();
        // dd($manager);
        if ($manager) {
            $user->line_manager_id=$manager->id;
            $user->save();
        }else{
            $user->line_manager_id=0;
            $user->save();
        }

        return 'success';
    }
    public function makePrimaryManager(Request $request)
    {
        $user=User::find($request->user_id);

        $user->managers()->detach($request->manager_id);
        $user->managers()->attach($request->manager_id);
        $user->line_manager_id=$request->manager_id;
        $user->save();
        return 'success';
    }
    public function viewNotifications(Request $request)
    {
        $pageType="mailbox";
        return view('notification.list',compact('pageType'));
    }
    public function clearNotification(Request $request)
    {
        $notification = Auth::user()->notifications()->where('id',request()->notification_id)->first();

        if($notification != null){
            $notification->markAsRead();
            return response()->json(['status'=>'success']);
        }
        // $user->unreadNotifications->markAsRead();
        // return 'success'
        // return view('notification.list',compact('pageType'));
    }
    public function clearNotifications(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
        // return 'success'
        // return view('notification.list',compact('pageType'));
    }
    public function viewNotificationInfo(Request $request)
    {
        $noti=Auth::user()->notifications()->where('id',$request->notification_id)->first();
        $noti->update(['read_at' => now()]);
        return view('notification.partials.info',compact('noti'));
    }
    public function getSeparationTypes(Request $request)
    {

        if($request->q==""){
            return "";
        }
        else{
            $name=\App\SeparationType::where('name','LIKE','%'.$request->q.'%')
                ->select('id as id','name as text')
                ->get();
        }


        return $name;


    }
    public function changePassword(Request $request)
    {

        if (Hash::check($request->password,  Auth::user()->password)) {
            $validator = Validator::make($request->all(),[
                'new_password' => ['required',
                    'min:8',
                    'confirmed']
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $validator->errors()
                ],401);
            }

            $request->user()->fill([
                'password' => Hash::make($request->new_password)
            ])->save();
            return 'success';
        }else{

            return 'failed';
        }
    }

    public function separations(Request $request)
    {
        $separations=\App\Separation::all();

        return $separations;
    }
    public function delete_separation(Request $request)
    {
        $separation=\App\Separation::find($request->separation_id);
        if ($separation) {
            $separation->delete();
        }


        return 'success';
    }
    public function save_separation(Request $request)
    {
        try{
            $company_id=companyId();
            $separation_type=\App\SeparationType::where('id',$request->separation_type)->orWhere('name','like','%'.$request->separation_type.'%')->first();
            if (!$separation_type) {
                $separation_type=\App\SeparationType::create(['name'=>$request->separation_type]);
            }
            $user=User::find($request->user_id);
            if ($user) {
                if ($user->hiredate=='1970-01-01') {
                    return 'no hiredate';
                }else{
                    $hiredate = \Carbon\Carbon::parse($user->hiredate);
                    $sepdate = \Carbon\Carbon::parse($request->dos);

                    $diff = $hiredate->diffInDays($sepdate);
                    \App\Separation::create(['user_id'=>$user->id,'separation_type_id'=>$separation_type->id,'date_of_separation'=>date('Y-m-d',strtotime($request->dos)),'days_of_employment'=>$diff,'hiredate'=>$user->hiredate,'comment'=>$request->comment,'company_id'=>$company_id]);
                    $user->status=2;
                    $user->save();
                }
                return 'success';
            }
            return 'failed';

        }catch(\Exception $ex){
            return $ex->getMessage();
        }
    }
    public function change_approval(Request $request){
        $changes=\App\UserTemp::where('last_change_approved',0)->where('company_id',companyId())->paginate(10);
        return view('empmgt.profile_change_list',compact('changes'));

    }
    public function nok_change_approval(Request $request){
        $company_id=companyId();
        $changes=\App\NokTemp::where('last_change_approved',0)->whereHas('user',function($query) use($company_id){
            $query->where('users.company_id',$company_id);
        })->paginate(10);
        return view('empmgt.nok_change_list',compact('changes'));

    }
    public function view_profile_change(Request $request){

            $activity=Activity::where(['causer_id'=>$request->user_id,'subject_id'=>$request->change_id])->where('subject_type', 'App\UserTemp')->orderByDesc('id')->first();
        $user=User::find($request->user_id);
//       return $activity->changes['old']['name'];


        return view('empmgt.partials.change_approval_info',compact('activity','user'));

    }
    public function approve_changes(Request $request){
//        return $request->all();
        $user=User::find($request->user_id);
        $user->update($request->all());
        $user_temp=UserTemp::where('user_id',$request->user_id)->first();
        $user_temp->update(['last_change_approved'=>1,'last_change_approved_on'=>date('Y-m-d H:i:s'),'last_change_approved_by'=>Auth::user()->id]);
        return 'success';
    }
    public function reject_changes(Request $request){

        $user_temp=\App\UserTemp::find($request->user_id);
        $user_temp->update(['last_change_approved'=>2,'last_change_approved_on'=>date('Y-m-d H:i:s'),'last_change_approved_by'=>Auth::user()->id]);
        return 'success';

    }
    public function view_nok_change(Request $request){
        $nok=\App\NokTemp::find($request->change_id);
      $activity=Activity::where(['causer_id'=>$nok->user_id])->where('subject_type', 'App\NokTemp')->orderByDesc('id')->first();

//        $user=User::find($nok->user_id);
//       return $activity->changes['old']['name'];
        return view('empmgt.partials.change_nok_approval_info',compact('activity','nok'));

    }
    public function approve_nok_changes(Request $request){
//        return $request->all();
        $nok=\App\Nok::where('user_id',$request->user_id)->first();
        if($nok){
            $nok->update($request->all());
            $nok_temp=\App\NokTemp::where('user_id',$request->user_id)->first();
            $nok_temp->update(['last_change_approved'=>1,'last_change_approved_on'=>date('Y-m-d H:i:s'),'last_change_approved_by'=>Auth::user()->id]);
        }else{
            Nok::create($request->all());
            $nok_temp=\App\NokTemp::where('user_id',$request->user_id)->first();
            $nok_temp->update(['last_change_approved'=>1,'last_change_approved_on'=>date('Y-m-d H:i:s'),'last_change_approved_by'=>Auth::user()->id]);

        }

        return 'success';
    }
    public function reject_nok_changes(Request $request){


        $nok_temp=\App\NokTemp::find($request->nok_id);
        $nok_temp->update(['last_change_approved'=>2,'last_change_approved_on'=>date('Y-m-d H:i:s'),'last_change_approved_by'=>Auth::user()->id]);
        return 'success';

    }
    public function educational_history_change_approval(Request $request){
        $changes=\App\EducationHistory::where('last_change_approved',0)->where('company_id',companyId())->paginate(10);
        return view('empmgt.educational_history_change_list',compact('changes'));

    }
    public function view_educational_history_change(Request $request){
        $activity=Activity::where(['causer_id'=>$request->emp_id,'subject_id'=>$request->change_id])->where('subject_type', 'App\EducationHistory')->latest()->first();
        $user=User::find($request->emp_id);
//


        return view('empmgt.partials.change_educational_history_approval_info',compact('activity','user'));

    }
    public function approve_educational_history_changes(Request $request){
//        return $request->all();
        $eh=\App\EducationHistory::find($request->eh_id);
        $eh->update(['last_change_approved'=>1,'last_change_approved_on'=>date('Y-m-d H:i:s'),'last_change_approved_by'=>Auth::user()->id]);
        return 'success';
    }
    public function reject_educational_history_changes(Request $request){
        $activity=Activity::where(['causer_id'=>$request->emp_id,'subject_id'=>$request->eh_id])->where('subject_type', 'App\EducationHistory')->latest()->first();
        if ($activity->description=='created'){
            $eh=\App\EducationHistory::find($request->eh_id);
            if ($eh){
                $eh->delete();
            }
        }elseif($activity->description=='updated'){
            $eh=\App\EducationHistory::find($request->eh_id);
            foreach($activity->changes['old'] as  $key=>$previous_value){
                $eh->update([$key=>$previous_value]);
            }
            $eh->update(['last_change_approved'=>2,'last_change_approved_on'=>date('Y-m-d H:i:s'),'last_change_approved_by'=>Auth::user()->id,]);
        }


        return 'success';

    }

    public function employment_history_change_approval(Request $request){
        $changes=\App\EmploymentHistory::where('last_change_approved',0)->where('company_id',companyId())->paginate(10);
        return view('empmgt.employment_history_change_list',compact('changes'));

    }
    public function view_employment_history_change(Request $request){
        $activity=Activity::where(['causer_id'=>$request->emp_id,'subject_id'=>$request->change_id])->where('subject_type', 'App\EmploymentHistory')->latest()->first();
        $user=User::find($request->emp_id);
//


        return view('empmgt.partials.change_employment_history_approval_info',compact('activity','user'));

    }
    public function approve_employment_history_changes(Request $request){
//        return $request->all();
        $eh=\App\EmploymentHistory::find($request->eh_id);
        $eh->update(['last_change_approved'=>1,'last_change_approved_on'=>date('Y-m-d H:i:s'),'last_change_approved_by'=>Auth::user()->id]);
        return 'success';
    }
    public function reject_employment_history_changes(Request $request){
        $activity=Activity::where(['causer_id'=>$request->emp_id,'subject_id'=>$request->eh_id])->where('subject_type', 'App\EmploymentHistory')->latest()->first();
        if ($activity->description=='created'){
            $eh=\App\EmploymentHistory::find($request->eh_id);
            if ($eh){
                $eh->delete();
            }
        }elseif($activity->description=='updated'){
            $eh=\App\EmploymentHistory::find($request->eh_id);
            foreach($activity->changes['old'] as  $key=>$previous_value){
                $eh->update([$key=>$previous_value]);
            }
            $eh->update(['last_change_approved'=>2,'last_change_approved_on'=>date('Y-m-d H:i:s'),'last_change_approved_by'=>Auth::user()->id,]);
        }


        return 'success';

    }
    public function dependant_change_approval(Request $request){
        $changes=\App\Dependant::where('last_change_approved',0)->where('company_id',companyId())->paginate(10);
        return view('empmgt.dependant_change_list',compact('changes'));

    }
    public function view_dependant_change(Request $request){
        $activity=Activity::where(['causer_id'=>$request->emp_id,'subject_id'=>$request->change_id])->where('subject_type', 'App\Dependant')->latest()->first();
        $user=User::find($request->emp_id);
//


        return view('empmgt.partials.change_dependant_approval_info',compact('activity','user'));

    }
    public function approve_dependant_changes(Request $request){
//        return $request->all();
        $eh=\App\Dependant::find($request->eh_id);
        $eh->update(['last_change_approved'=>1,'last_change_approved_on'=>date('Y-m-d H:i:s'),'last_change_approved_by'=>Auth::user()->id]);
        return 'success';
    }
    public function reject_dependant_changes(Request $request){
        $activity=Activity::where(['causer_id'=>$request->emp_id,'subject_id'=>$request->eh_id])->where('subject_type', 'App\Dependant')->latest()->first();
        if ($activity->description=='created'){
            $eh=\App\Dependant::find($request->eh_id);
            if ($eh){
                $eh->delete();
            }
        }elseif($activity->description=='updated'){
            $eh=\App\Dependant::find($request->eh_id);
            foreach($activity->changes['old'] as  $key=>$previous_value){
                $eh->update([$key=>$previous_value]);
            }
            $eh->update(['last_change_approved'=>2,'last_change_approved_on'=>date('Y-m-d H:i:s'),'last_change_approved_by'=>Auth::user()->id,]);
        }


        return 'success';

    }



}
