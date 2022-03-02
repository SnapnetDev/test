<?php
namespace App\Traits;

use App\DocumentRequestType;
use App\Notifications\ApproveSeparation;
use App\Notifications\CompleteSeparationForm;
use App\Notifications\SeparationApproved;
use App\Notifications\SeparationNotifyStaff;
use App\Notifications\SeparationPassedStage;
use App\Notifications\SeparationRejected;
use App\SeparationApproval;
use App\SeparationApprovalList;
use App\SeparationForm;
use App\SeparationFormDetail;
use App\SeparationPolicy;
use App\SeparationQuestion;
use App\SeparationQuestionCategory;
use App\SeparationQuestionOption;
use App\Stage;
use App\Workflow;
use Illuminate\Http\Request;
use App\Separation;
use App\SeparationType;
use App\User;
use App\Suspension;
use App\SuspensionDeduction;
use App\SuspensionType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

use Excel;
use PDF;
/**
 *
 */
trait SeparationTrait
{
    public $allowed=['JPG','PNG','jpeg','png','gif','jpg','pdf'];
    public function processGet($route,Request $request)
    {
        switch ($route) {
            case 'seperations':
                return $this->index($request);
                break;
            case 'delete_separation':
                return $this->delete_separation($request);
                break;
            case 'view_separation':
                return $this->view_separation($request);
                break;
            case 'suspensions':
                return $this->suspensions($request);
                break;
            case 'delete_suspension':
                return $this->delete_suspension($request);
                break;
            case 'suspension':
                return $this->suspension($request);
                break;
            case 'test_month':
                return $this->test_month($request);
                break;
            case 'separation_types':
                return $this->getSeparationTypes($request);
                break;
            case 'suspension_types':
                return $this->getSuspensionTypes($request);
                break;
            case 'settings':
                return $this->settings($request);
                break;
            case 'get_separation_type':
                return $this->getSeparationType($request);
                break;
            case 'delete_separation_type':
                return $this->deleteSeparationType($request);
                break;
            case 'get_separation_approval_list':
                return $this->getSeparationApprovalList($request);
                break;
            case 'delete_separation_approval_list':
                return $this->deleteSeparationApprovalList($request);
                break;
            case 'get_separation_question_category':
                return $this->getSeparationQuestionCategory($request);
                break;
            case 'delete_separation_question_category':
                return $this->deleteSeparationQuestionCategory($request);
                break;
            case 'questions':
                return $this->separationQuestions($request);
                break;
            case 'get_separation_question':
                return $this->getSeparationQuestion($request);
                break;
            case 'get_separation_question_options':
                return $this->getSeparationQuestionOptions($request);
                break;
            case 'delete_separation_question':
                return $this->deleteSeparationQuestion($request);
                break;
            case 'delete_separation_question_option':
                return $this->deleteSeparationQuestionOption($request);
                break;
            case 'separation_form':
                return $this->separationForm($request);
                break;
            case 'separation_form_review':
                return $this->separationFormReview($request);
                break;
            case 'approvals':
                return $this->approvals($request);
                break;
            case 'download_exit_interview_form':
                return $this->downloadInterviewForm($request);
                break;
            case 'download_exit_clearance_form':
                return $this->downloadClearanceForm($request);
                break;
            case 'notify_staff_separation':
                return $this->notify_staff_separation($request);
                break;



            default:
                return $this->index($request);
                break;
        }
    }

    public function processPost(Request $request)
    {
        switch ($request->type) {

            case 'save_separation':
                return $this->save_separation($request);
                break;
            case 'save_suspension':
                return $this->save_suspension($request);
                break;
            case 'save_separation_type':
                return $this->saveSeparationType($request);
                break;
            case 'save_separation_question_category':
                return $this->saveSeparationQuestionCategory($request);
                break;
            case 'save_separation_policy':
                return $this->saveSeparationPolicy($request);
                break;
            case 'save_separation_question':
                return $this->saveSeparationQuestion($request);
                break;
            case 'save_separation_question_option':
                return $this->saveSeparationQuestionOption($request);
                break;
            case 'save_separation_form':
                return $this->saveSeparationForm($request);
                break;
            case 'save_separation_approval_list':
                return $this->saveSeparationApprovalList($request);
                break;
            case 'save_approval':
                return $this->saveApproval($request);
                 break;
            case 'save_signature':
                return $this->saveSignature($request);
                break;



            default:
                # code...
                break;
        }
    }
    public function separations(Request $request)
    {
        $separations=\App\Separation::all();

        return $separations;
    }
    public function view_separation(Request $request)
    {
        $sp=SeparationPolicy::where('company_id',companyId())->first();
        $separation=\App\Separation::find($request->separation_id);

        if ($separation) {
            return view('empmgt.view_separation',compact('separation','sp'));
        }
    }
    public function delete_separation(Request $request)
    {
        $separation=\App\Separation::find($request->separation_id);
        if ($separation) {
            $separation->delete();
            $separation->user->status=1;
            $separation->user->save();

            return redirect()->back()->with(['success'=>'Separation Deleted Successfully']);
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
                    $separation_policy=SeparationPolicy::where('company_id',$company_id)->first();
                    $stage = ($separation_policy->employee_fills_form==1) ? 1 :($separation_policy->use_approval_process==1 ? 2 : 3);
                    $diff = $hiredate->diffInDays($sepdate);
                    $sep=\App\Separation::create(['user_id'=>$user->id,'separation_type_id'=>$separation_type->id,'date_of_separation'=>date('Y-m-d',strtotime($request->dos)),'days_of_employment'=>$diff,'hiredate'=>$user->hiredate,'comment'=>$request->comment,'company_id'=>$company_id,'workflow_id'=>$separation_policy->workflow_id,'stage'=>$stage,'approved'=>0,'initiator_id'=>\Auth::user()->id]);
                    // $user->status=2;
                    // $user->save();
                    if ($request->file('exit_interview_form')) {
                        $path = $request->file('exit_interview_form')->store('separation');
                        if (Str::contains($path, 'separation')) {
                            $filepath= Str::replaceFirst('separation', '', $path);
                        } else {
                            $filepath= $path;
                        }
                        $sep->exit_interview_form = $filepath;
                        $sep->save();
                    }
                    if ($request->file('exit_checkout_form')) {
                        $path = $request->file('exit_checkout_form')->store('separation');
                        if (Str::contains($path, 'separation')) {
                            $filepath= Str::replaceFirst('separation', '', $path);
                        } else {
                            $filepath= $path;
                        }
                        $sep->exit_checkout_form = $filepath;
                        $sep->save();
                    }
                    if ($separation_policy->employee_fills_form==1){
                        $sep->user->notify(new CompleteSeparationForm($sep));
                        $sep->stage=1;
                        $sep->save();
                    }else{
                        $sep->stage=3;
                        $sep->save();
                    }
                }
                return 'success';
            }
            return 'failed';

        }catch(\Exception $ex){
            return $ex->getMessage();
        }
    }
    public function suspensions(Request $request)
    {
        $suspensions=Suspension::paginate(10);
        return view('empmgt.suspensions',compact('suspensions'));
    }
    public function suspension(Request $request)
    {
        return $suspension=\App\Suspension::find($request->separation_id);


    }

    public function test_month()
    {
        $startdate = \Carbon\Carbon::parse('2019-04-15');
        $enddate = \Carbon\Carbon::parse('2020-05-23');

        if (intval(date('Y',strtotime($enddate))) > intval(date('Y',strtotime($startdate)))) {
            $diffyear=intval(date('Y',strtotime($enddate))) -intval(date('Y',strtotime($startdate)));
            $diffmonths=(intval(date('m',strtotime($enddate)))+(12*$diffyear)) - (intval(date('m',strtotime($startdate))));

        }else{
            $diffmonths=(intval(date('m',strtotime($enddate)))) - (intval(date('m',strtotime($startdate))));
        }
        $array=[];
        if ($diffmonths>0) {
            for ($i=0; $i <=$diffmonths ; $i++) {
                if ($i==0) {
                    // $array[]= $days=cal_days_in_month(CAL_GREGORIAN,intval(date('m',strtotime($startdate))),intval(date('Y',strtotime($startdate))))-intval(date('d',strtotime($startdate)))+1;
                    $array[]= $days=$this->getExpectedDays(intval(date('m',strtotime($startdate))),intval(date('Y',strtotime($startdate))),intval(date('d',strtotime($startdate))));
                }elseif($i==$diffmonths){
                    // $array[]= $days=intval(date('d',strtotime($enddate)));
                    $array[]= $days=$this->getExpectedDays(intval(date('m',strtotime($enddate))),intval(date('Y',strtotime($enddate))),1,intval(date('d',strtotime($enddate))));
                }else{
                    $month=intval(date('m',strtotime($startdate ."+$i months")));
                    $year=intval(date('Y',strtotime($startdate ."+$i months")));
                    // $array[]= $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
                    $array[]= $days=$this->getExpectedDays($month,$year);
                }
            }
            return $array;
        }else{
            return $days=intval(date('d',strtotime($enddate)))-intval(date('d',strtotime($startdate)))+1;
        }

        // return $startdate->diffInMonths($enddate);

    }
    public function checkHoliday($date)
    {
        $has_holiday=\App\Holiday::whereDate('date', $date)->first();
        $retVal = ($has_holiday) ? true : false ;
        return $retVal;
    }
    public function getExpectedDays($month,$year,$start=1,$end=0)
    {
        $total_days=0;
        $company_id=companyId();
        if($end==0){
            $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        }else{
            $days=$end;
        }

        $pp=\App\PayrollPolicy::where('company_id',$company_id)->first();
        if ($start==1) {
            for ($i=1; $i <=$days ; $i++) {
                if($pp->payroll_runs==1){

                    $total_days++;

                }else{
                    if (date('N',strtotime("$year-$month-$i"))<6 && $this->checkHoliday("$year-$month-$i")==false) {
                        $total_days++;
                    }
                }
            }
            return $total_days;
        } elseif($start>1) {
            for ($i=$start; $i <=$days ; $i++) {
                if($pp->payroll_runs==1){

                    $total_days++;

                }else{
                    if (date('N',strtotime("$year-$month-$i"))<6 && $this->checkHoliday("$year-$month-$i")==false) {
                        $total_days++;
                    }
                }
            }


            return $total_days;
        }
    }
    public function save_suspension(Request $request)
    {
        try{
            $company_id=companyId();
            $suspension_type=\App\SuspensionType::where('id',$request->suspension_type)->orWhere('name','like','%'.$request->suspension_type.'%')->first();
            if (!$suspension_type) {
                $suspension_type=\App\SuspensionType::create(['name'=>$request->suspension_type]);
            }
            $user=User::find($request->user_id);

            if ($user) {
                $startdate = \Carbon\Carbon::parse($request->startdate);
                $enddate = \Carbon\Carbon::parse($request->enddate);

                $diff = $startdate->diffInDays($enddate);
                $suspension=Suspension::create(['user_id'=>$user->id,'suspension_type_id'=>$suspension_type->id,'start_date'=>date('Y-m-d',strtotime($request->startdate)),'end_date'=>date('Y-m-d',strtotime($request->enddate)),'length'=>$request->length,'comment'=>$request->comment,'company_id'=>$company_id,'created_by'=>Auth::user()->id]);
                //get deductable days
                if (intval(date('Y',strtotime($enddate))) > intval(date('Y',strtotime($startdate)))) {
                    $diffyear=intval(date('Y',strtotime($enddate))) -intval(date('Y',strtotime($startdate)));
                    $diffmonths=(intval(date('m',strtotime($enddate)))+(12*$diffyear)) - (intval(date('m',strtotime($startdate))));

                }else{
                    $diffmonths=(intval(date('m',strtotime($enddate)))) - (intval(date('m',strtotime($startdate))));
                }
                if ($diffmonths>0) {
                    for ($i=0; $i <=$diffmonths ; $i++) {
                        if ($i==0) {

                            $days=$this->getExpectedDays(intval(date('m',strtotime($startdate))),intval(date('Y',strtotime($startdate))),intval(date('d',strtotime($startdate))));
                            $dt='01-'.intval(date('m',strtotime($startdate))).'-'.intval(date('Y',strtotime($startdate)));
                            $suspension_deduction=\App\SuspensionDeduction::create(['date'=>date('Y-m-d',strtotime($dt)),'days'=>$days,'suspension_id'=>$suspension->id,'deducted'=>0]);
                        }elseif($i==$diffmonths){

                            $days=$this->getExpectedDays(intval(date('m',strtotime($enddate))),intval(date('Y',strtotime($enddate))),1,intval(date('d',strtotime($enddate))));
                            $dt='01-'.intval(date('m',strtotime($enddate))).'-'.intval(date('Y',strtotime($enddate)));
                            $suspension_deduction=\App\SuspensionDeduction::create(['date'=>date('Y-m-d',strtotime($dt)),'days'=>$days,'suspension_id'=>$suspension->id,'deducted'=>0]);
                        }else{
                            $month=intval(date('m',strtotime($startdate ."+$i months")));
                            $year=intval(date('Y',strtotime($startdate ."+$i months")));
                            $dt='01-'.$month.'-'.$year;
                            $days=$this->getExpectedDays($month,$year);
                            $suspension_deduction=\App\SuspensionDeduction::create(['date'=>date('Y-m-d',strtotime($dt)),'days'=>$days,'suspension_id'=>$suspension->id,'deducted'=>0]);
                        }
                    }

                }else{

                    $days=$this->getExpectedDays(intval(date('m',strtotime($startdate))),intval(date('d',strtotime($startdate))),intval(date('d',strtotime($startdate))),intval(date('d',strtotime($enddate))));
                    $dt='01-'.intval(date('m',strtotime($startdate))).'-'.intval(date('Y',strtotime($startdate)));
                    $suspension_deduction=\App\SuspensionDeduction::create(['date'=>date('Y-m-d',strtotime($dt)),'days'=>$days,'suspension_id'=>$suspension->id,'deducted'=>0]);
                }
                return 'success';
            }
            return 'failed';

        }catch(\Exception $ex){
            return $ex->getMessage();
        }
    }
    public function delete_suspension(Request $request)
    {
        $suspension=\App\Suspension::find($request->suspension_id);
        if ($suspension) {
            if ($suspension->suspension_deductions->contains('deducted', 1)) {
                return redirect()->back()->with(['Error'=>'Suspension is in use and cannot be deleted']);
            }else{
                $suspension->delete();
                $suspension->user->status=1;
                $suspension->user->save();
            }


            return redirect()->back()->with(['success'=>'Suspension Deleted Successfully']);
        }


        return 'success';
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
    public function getSuspensionTypes(Request $request)
    {

        if($request->q==""){
            return "";
        }
        else{
            $name=\App\SuspensionType::where('name','LIKE','%'.$request->q.'%')
                ->select('id as id','name as text')
                ->get();
        }


        return $name;


    }

    public function settings(Request $request){
        $company_id=companyId();
        $workflows=Workflow::where('status',1)->get();
        $sp=SeparationPolicy::where('company_id',$company_id)->first();
        $workflows=Workflow::all();


        if (!$sp) {
            $sp=SeparationPolicy::create(['employee_fills_form'=>0,'use_approval_process'=>0,'prorate_salary'=>1,'notify_staff_on_exit'=>0,'company_id'=>$company_id,'workflow_id'=>0]);
        }
        $separation_types=SeparationType::all();
        $separation_approval_lists=SeparationApprovalList::all();
        $separation_question_categories=SeparationQuestionCategory::where('company_id',$company_id)->get();

        return view('settings.separationsettings.index',compact('separation_types','workflows','sp','separation_question_categories','separation_approval_lists'));
    }
    public function saveSeparationPolicy(Request $request)
    {
        $company_id=companyId();
        $sp=SeparationPolicy::where('company_id',$company_id)->first();
        $sp->update(['employee_fills_form'=>$request->employee_fills_form,'use_approval_process'=>$request->use_approval_process,'prorate_salary'=>$request->prorate_salary,'notify_staff_on_exit'=>$request->notify_staff_on_exit,'workflow_id'=>$request->workflow_id]);
        return  response()->json('success',200);
    }
    public function saveSeparationQuestionCategory(Request $request)
    {
        SeparationQuestionCategory::updateOrCreate(['id'=>$request->separation_category_id],['name'=>$request->name,'created_by'=>Auth::user()->id,'company_id'=>companyId()]);
        return  response()->json('success',200);
    }
    public function getSeparationQuestionCategory(Request $request)
    {
        $separation_question_category=SeparationQuestionCategory::find($request->separation_category_id);
        return  response()->json($separation_question_category,200);
    }
    public function deleteSeparationQuestionCategory(Request $request)
    {
        $separation_question_category=SeparationQuestionCategory::find($request->separation_category_id);

        if ($separation_question_category) {
            $separation_question_category->delete();
        }else{
            return  response()->json(['failed'],200);
        }
        return  response()->json(['success'],200);
    }
    
    public function saveSeparationType(Request $request)
    {
        SeparationType::updateOrCreate(['id'=>$request->separation_type_id],['name'=>$request->name]);
        return  response()->json('success',200);
    }
    
    public function getSeparationType(Request $request)
    {
        $separation_type=SeparationType::find($request->separation_type_id);
        return  response()->json($separation_type,200);
    }
    public function deleteSeparationType(Request $request)
    {
        $separation_type=SeparationType::find($request->separation_type_id);
        if ($separation_type) {
            $separation_type->delete();
        }else{
            return  response()->json(['failed'],200);
        }
        return  response()->json(['success'],200);
    }
    public function saveSeparationApprovalList(Request $request)
    {
        SeparationApprovalList::updateOrCreate(['id'=>$request->separation_approval_list_id],['name'=>$request->name,'created_by'=>Auth::user()->id,'company_id'=>companyId()]);
        return  response()->json('success',200);
    }
    public function getSeparationApprovalList(Request $request)
    {
        $separation_approval_list=SeparationApprovalList::find($request->separation_approval_list_id);
        return  response()->json($separation_approval_list,200);
    }
    public function deleteSeparationApprovalList(Request $request)
    {
        $separation_approval_list=SeparationApprovalList::find($request->separation_approval_list_id);
        if ($separation_approval_list) {
            $separation_approval_list->delete();
        }else{
            return  response()->json(['failed'],200);
        }
        return  response()->json(['success'],200);
    }

    public function separationQuestions(Request $request)
    {
        $option_questions=SeparationQuestion::where(function($query){
            $query->where('type','rad')
                ->orWhere('type','chk');
        })->where('company_id',companyId())->get();
        $questions=SeparationQuestion::where('company_id',companyId())->get();
        $question_categories=SeparationQuestionCategory::where('company_id',companyId())->get();
        return view('separations.questions',compact('question_categories','questions','option_questions'));
    }
    public function saveSeparationQuestion(Request $request)
    {
        $separation_question=SeparationQuestion::updateOrCreate(['id'=>$request->separation_question_id],['question'=>$request->question,'status'=>$request->status,'compulsory'=>$request->compulsory,'type'=>$request->question_type,'separation_question_category_id'=>$request->separation_question_category_id,'created_by'=>Auth::user()->id,'company_id'=>companyId()]);


//        return $separation_question;
        return  response()->json('success',200);
    }
    public function getSeparationQuestion(Request $request)
    {
        $separation_question=SeparationQuestion::find($request->separation_question_id);
        return  response()->json($separation_question,200);
    }
    public function getSeparationQuestionOptions(Request $request)
    {
        $separation_question_options=SeparationQuestionOption::whereHas('question',function($query){
            $query->where('type','rad')
                ->orWhere('type','chk');
        })->where(['separation_question_id'=>$request->separation_question_id])->get();
        return  response()->json($separation_question_options,200);
    }
    public function saveSeparationQuestionOption(Request $request)
    {
        $separation_question_option=SeparationQuestionOption::updateOrCreate(['id'=>$request->id],['separation_question_id'=>$request->separation_question_id,'value'=>$request->value]);
        return $separation_question_option;


    }
    public function deleteSeparationQuestion(Request $request)
    {
        $separation_question=SeparationQuestion::find($request->separation_question_id);
        if ($separation_question) {
            foreach ($separation_question->options as $option){
                $option->delete();
            }
            $separation_question->delete();
        }else{
            return  response()->json(['failed'],200);
        }
        return  response()->json(['success'],200);
    }
    public function deleteSeparationQuestionOption(Request $request)
    {
        $separation_question_option=SeparationQuestionOption::find($request->id);

        if ($separation_question_option) {
            $separation_question_option->delete();
        }else{
            return  response()->json(['failed'],200);
        }
        return  response()->json(['success'],200);
    }
    public function separationForm(Request $request)
    {
//    return $request->separation;
       $separation=Separation::where('id', $request->separation)->withoutGlobalScopes()->first();
        if ($separation){
            $questions=SeparationQuestion::where(['company_id'=>companyId(),'status'=>1])->get();
            $question_categories=SeparationQuestionCategory::where('company_id',companyId())->get();
            return view('separations.exitform',compact('question_categories','questions','separation'));
        }else{
           return redirect()->back();
        }


    }
    public function separationFormReview(Request $request)
    {
        $user=User::find(1);
        $separation_form=SeparationForm::where('user_id',$user->id)->first();
        return view('separations.exitformreview',compact('user','separation_form'));
    }
//    public function separationApproval(Request $request)
//    {
//        $user=User::find(1);
//        $separation_form=SeparationForm::where('user_id',$user->id)->first();
//        return view('separations.exitformreview',compact('user','separation_form'));
//    }

    public function saveSignature(Request $request)
    {

        $result = array();
        $imagedata = base64_decode($request->img_data);

        // $filename = md5(date("dmYhisA"));
        $filename = Auth::user()->name.'-'.date('d-M-Y');
        //Location to where you want to created sign image
        $file_name = 'public/e-signature/'.$filename.'.png';
        \Storage::disk('local')->put($file_name, $imagedata);
        // $path=$imagedata->store('file');
        // file_put_contents($file_name, $imagedata);
        $result['status'] = 1;
        $result['file_name'] = $file_name;


        if ($request->session()->has('vendor_id'))
        {
            $vendor=\App\Vendor::find(session('vendor_id'));
            $vendor->signatures()->create(['signature' => $filename.'.png', 'contract_id' => $request->contract_id]);
        }
        elseif(\Auth::user())
        {
            \Auth::user()->signatures()->create(['signature' => $filename.'.png', 'contract_id' => $request->contract_id]);
        }

        return json_encode($result);

        // return redirect()->back('');

    }
    public function notify_staff_separation(Request $request)
    {
        $separation=Separation::where('id', $request->separation_id)->withoutGlobalScopes()->first();
        $users=User::where('status','!=',2)->get();
        foreach ($users as $user){
            $user->notify( new SeparationNotifyStaff($separation));
        }
        if ($separation) {
            foreach ($users as $user){
                $user->notify( new SeparationNotifyStaff($separation));
            }

            return redirect()->back()->with(['success'=>'Staff Notified Successfully']);
        }


        return 'success';
    }

    public  function saveSeparationForm(Request $request)
    {
        $company_id=companyId();
        $separation_policy=SeparationPolicy::where('company_id',$company_id)->first();
        $stage = $separation_policy->use_approval_process==1 ? 2 : 3;
        $status= $separation_policy->use_approval_process==1 ? 0 : 1;
        $separation=Separation::find($request->separation_id);

        $sf=SeparationForm::create(['user_id'=>Auth::user()->id,'status'=>$status,'company_id'=>companyId(),'separation_id'=>$separation->id,'handover_note'=>$request->handover_note]);
        $separation->update(['stage'=>$stage]);
        $sqs=SeparationQuestion::where(['company_id'=>companyId()])->get();

        foreach ($sqs as $sq) {
            if ($request->filled('question_'.$sq->id)) {
                if ($sq->type == 'txt') {
                    SeparationFormDetail::create(['separation_question_id' => $sq->id, 'separation_form_id' => $sf->id,
                        'answer' => $request->input('question_'.$sq->id), 'type'=>'txt']);
                }elseif($sq->type=='rad'){
                    SeparationFormDetail::create(['separation_question_id' => $sq->id, 'separation_form_id' => $sf->id,
                        'separation_question_option_id' => $request->input('question_'.$sq->id), 'type'=>'rad']);
                }elseif($sq->type=='chk'){
                $sfd=SeparationFormDetail::create(['separation_question_id' => $sq->id, 'separation_form_id' => $sf->id, 'type'=>'chk']);
                $selected=count($request->input('question_'.$sq->id));
                for ($i=0; $i < $selected;$i++) {
                    $value=$request->input('question_'.$sq->id)[$i];
                    $sfd->options()->attach($value);
                }

            }
        }
        }
        $this->start_separation_approval($request);

        return 'success';

    }
    public function start_separation_approval(Request $request){
        $company_id=companyId();
        $separation=Separation::find($request->separation_id);
        $separation_policy=SeparationPolicy::where('company_id',$company_id)->first();

        if ($separation_policy->use_approval_process==1 & $separation->workflow_id>0){
            $separation->stage=2;
            $separation->save();
            $stage = Workflow::find($separation->workflow_id)->stages->first();
            if ($stage->type == 1) {
                $separation->separation_approvals()->create([ 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => $stage->user_id,'company_id'=>companyId()
                ]);
                if ($stage->user) {
                    $stage->user->notify(new ApproveSeparation($separation));
                }

            } elseif ($stage->type == 2) {
                $separation->separation_approvals()->create([ 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => 0,'company_id'=>companyId()
                ]);
                if ($stage->role->manages == 'dr') {
                    if ($separation->user->managers) {
                        foreach ($separation->user->managers as $manager) {
                            $manager->notify(new ApproveSeparation($separation));
                        }
                    }
                }elseif($stage->role->manages == 'ss') {

                    foreach ($separation->user->plmanager->managers as $manager) {
                        $manager->notify(new ApproveSeparation($separation));
                    }
                } elseif ($stage->role->manage == 'all') {
                    foreach ($stage->role->users as $user) {
                        $user->notify(new ApproveSeparation($separation));
                    }
                } elseif ($stage->role->manage == 'none') {
                    foreach ($stage->role->users as $user) {
                        $user->notify(new ApproveSeparation($separation));
                    }
                }
            } elseif ($stage->type == 3) {
                $separation->separation_approvals()->create([ 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => 0,'company_id'=>companyId()
                ]);
                if ($stage->group) {
                    foreach ($stage->group->users as $user) {
                        $user->notify(new ApproveSeparation($separation));
                    }
                }

            }
            return 'success';
        }
        return 'error';
    }
    public function saveApproval(Request $request)
    {

//         $imagedata = base64_decode($request->img_data);
//
//        $filename = Auth::user()->name.'-'.date('d-M-Y');
//        //Location to where you want to created sign image
//        $file_name = 'approvals/'.$filename.'.png';
//        Storage::put($file_name, $imagedata);

        $separation_approval = SeparationApproval::find($request->separation_approval_id);
        $separation_approval->comments = $request->comment;
        if ($request->approval == 1) {
            $separation_approval->status = 1;
            $separation_approval->approver_id = Auth::user()->id;
//            $separation_approval->signature = $filename.'.png';
            $separation_approval->save();
            if($request->filled('approval_list')){
                $separation_approval->lists()->attach($request->input('approval_list'));
            }

            // $logmsg=$leave_approval->document->filename.' was approved in the '.$leave_approval->stage->name.' in the '.$leave_approval->stage->workflow->name;
            // $this->saveLog('info','App\Review',$leave_approval->id,'leave_approvals',$logmsg,Auth::user()->id);
            $newposition = $separation_approval->stage->position + 1;
            $nextstage = Stage::where(['workflow_id' => $separation_approval->stage->workflow->id, 'position' => $newposition])->first();
            // return $review->stage->position+1;
            // return $nextstage;

            if ($nextstage) {

                $newseparation_approval = new SeparationApproval();
                $newseparation_approval->stage_id = $nextstage->id;
                $newseparation_approval->separation_id = $separation_approval->separation->id;
                $newseparation_approval->status = 0;
                $newseparation_approval->company_id = companyId();

                $newseparation_approval->save();
                // $logmsg='New review process started for '.$newleave_approval->document->filename.' in the '.$newleave_approval->stage->workflow->name;
                // $this->saveLog('info','App\Review',$leave_approval->id,'reviews',$logmsg,Auth::user()->id);
                if ($nextstage->type == 1) {

                    // return $nextstage->type . '-2--' . 'all';

                    $nextstage->user->notify(new ApproveSeparation($newseparation_approval->separation));
                } elseif ($nextstage->type == 2) {
                    // return $nextstage->role->manages . '1---' . 'all' . json_encode($leave_approval->separation->user->managers);
                    if ($nextstage->role->manages == 'dr') {

                        // return $nextstage->role->manage . '---' . json_encode($nextstage->role->users);

                        foreach ($separation_approval->separation->user->managers as $manager) {
                            $manager->notify(new ApproveSeparation($newseparation_approval->separation));
                        }
                    }elseif($nextstage->role->manages == 'ss') {

                        foreach ($separation_approval->separation->user->plmanager->managers as $manager) {
                            $manager->notify(new ApproveSeparation($newseparation_approval->separation));
                        }
                    } elseif ($nextstage->role->manages == 'all') {
                        // return 'all.';

                        // return $nextstage->role->manage . '---' . json_encode($nextstage->role->users);

                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApproveSeparation($newseparation_approval->separation));
                        }
                    } elseif ($nextstage->role->manages == 'none') {
                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApproveSeparation($newseparation_approval->separation));
                        }
                    }
                } elseif ($nextstage->type == 3) {
                    //1-user
                    //2-role
                    //3-groups
                    // return 'not_blank';

                    foreach ($nextstage->group->users as $user) {
                        $user->notify(new ApproveSeparation($newseparation_approval->separation));
                    }
                } else {
                    // return 'blank';
                }

                $separation_approval->separation->initiator->notify(new SeparationPassedStage($separation_approval, $separation_approval->stage, $newseparation_approval->stage));
            } else {
                // return 'blank2';
                $separation_approval->separation->approved = 1;
                $separation_approval->separation->save();

                $separation_approval->separation->initiator->notify(new SeparationApproved($separation_approval->stage, $separation_approval));
                $separation_approval->separation->update(['stage'=>3]);
            }


        } elseif ($request->approval == 2) {
            // return 'blank3';
            $separation_approval->status = 2;
            $separation_approval->comments = $request->comment;
            $separation_approval->approver_id = Auth::user()->id;
            $separation_approval->save();
            // $logmsg=$leave_approval->document->filename.' was rejected in the '.$leave_approval->stage->name.' in the '.$leave_approval->stage->workflow->name;
            // $this->saveLog('info','App\Review',$leave_approval->id,'leave_approvals',$logmsg,Auth::user()->id);
            $separation_approval->separation->status = 2;
            $separation_approval->separation->save();
            $separation_approval->separation->initiator->notify(new SeparationRejected($separation_approval->stage, $separation_approval));
            // return redirect()->route('documents.mypendingleave_approvals')->with(['success'=>'Document Reviewed Successfully']);
        }

        return 'success';


        // return redirect()->route('documents.mypendingreviews')->with(['success'=>'Leave Request Approved Successfully']);
    }
    public function approvals(Request $request)
    {
        $user = Auth::user();
        $sals=SeparationApprovalList::where('company_id',companyId())->get();
        $user_approvals = $this->userApprovals($user);
        $dr_approvals = $this->getDRSeparationApprovals($user);
        $ss_approvals = $this->getSSSeparationApprovals($user);
        $role_approvals = $this->roleApprovals($user);
        $group_approvals = $this->groupApprovals($user);

        return view('separations.approvals', compact('sals','user_approvals', 'role_approvals', 'group_approvals', 'dr_approvals','ss_approvals'));
    }

//    public function departmentApprovals(Request $request)
//    {
//        $user = Auth::user();
//        $dapprovals = SeparationApproval::whereHas('payroll.user.job.department', function ($query) use ($user) {
//            $query->where('payroll.user_id', '!=', $user->id)
//                ->where('departments.manager_id', $user->id);
//
//        })
//            ->where('status', 0)->orderBy('id', 'asc')->get();
//        return view('compensation.department_approvals', compact('dapprovals'));
//    }

    public function userApprovals(User $user)
    {
        return $las = SeparationApproval::whereHas('stage.user', function ($query) use ($user) {
            $query->where('users.id', $user->id);

        })
            ->where('status', 0)->orderBy('id', 'asc')->get();

    }
    public function getSSSeparationApprovals(User $user)
    {
        return Auth::user()->getSSSeparationApprovals();
        // 	return $las = SeparationApproval::whereHas('stage.role.users',function($query) use($user){
        // 	$query->where('users.id',$user->id);
        // })

        //  ->where('status',0)->orderBy('id','desc')->get();

    }

    public function getDRSeparationApprovals(User $user)
    {
        return Auth::user()->getDRSeparationApprovals();
        // 	return $las = SeparationApproval::whereHas('stage.role.users',function($query) use($user){
        // 	$query->where('users.id',$user->id);
        // })

        //  ->where('status',0)->orderBy('id','desc')->get();

    }

    public function roleApprovals(User $user)
    {
        return $las = SeparationApproval::whereHas('stage.role', function ($query) use ($user) {
            $query->where('manages', '!=', 'dr')
                ->where('manages', '!=', 'ss')
                ->where('roles.id', $user->role_id);
        })->where('status', 0)->orderBy('id', 'asc')->get();
    }

    public function groupApprovals(User $user)
    {
        return $las = SeparationApproval::whereHas('stage.group.users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
            ->where('status', 0)->orderBy('id', 'asc')->get();

    }
    public function downloadInterviewForm(Request $request)
    {
        $company_id=companyId();
        $company=\App\Company::find($company_id);
        $image=url('uploads/logo').$company->logo;
        $separation=Separation::find($request->separation_id);
            $pdf = PDF::loadView('separations.partials.exitinterviewform', compact('separation','company'));
            $pdf->setWatermarkImage($image, $opacity = 0.3, $top = '30%', $width = '100%', $height = '100%');
        return $pdf->stream(Auth::user()->name.'.pdf');
    }
    public function downloadClearanceForm(Request $request)
    {$company_id=companyId();
        $company=\App\Company::find($company_id);
        $image=url('uploads/logo').$company->logo;
        $separation=Separation::find($request->separation_id);
        $pdf = PDF::loadView('separations.partials.exitclearanceform', compact('separation','company'));
        $pdf->setWatermarkImage($image, $opacity = 0.3, $top = '30%', $width = '100%', $height = '100%');
        return $pdf->stream(Auth::user()->name.'.pdf');
    }






}
