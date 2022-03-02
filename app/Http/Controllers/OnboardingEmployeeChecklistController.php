<?php

namespace App\Http\Controllers;

use App\OnboardingChecklist;
use App\OnboardingEmployeeChecklist;
use App\User;
use Illuminate\Http\Request;
use Auth;
use phpDocumentor\Reflection\Types\Collection;

class OnboardingEmployeeChecklistController extends Controller
{

    public function index()
    {

        $list = User::fetch()->paginate(20) ;
        $records = [];

        foreach ($list as $key=>$item){

            $item->percentage_progress = 0; //OnboardingChecklist::getProgress($item->id);
            $item->overall_progress = 0;
            $item->current_handler = 'N/A';
            $item->current_step = 'Pending';
            $item->current_action = 'Pending';
            $item->current_document = 'Not Uploaded';
            $item->confirmed_by = 'N/A';
            $item->status = 'Pending';
            $item->stage = 'N/A';
            $item->has_document = false;
            $item->current_document = '';

            if (OnboardingEmployeeChecklist::currentStage($item->id)->exists()){

                $currentStage = OnboardingEmployeeChecklist::currentStage($item->id)->first();
                $item->has_document = !empty($currentStage->support_document);
                $item->current_document = $currentStage->support_document;
//                dd($currentStage);
//                $item->percentage_progress = OnboardingChecklist::getProgress($currentStage->onboarding_check_list_id,$item->id);
                $item->percentage_progress = OnboardingChecklist::getOverallProgress($item->id);
                $item->current_handler = OnboardingChecklist::getAssignedPersonnel($currentStage->onboarding_check_list_id)->original_name;
                $checklist = OnboardingChecklist::getOne($currentStage->onboarding_check_list_id)->first();
                $item->stage = $checklist->action;
//                dd(OnboardingChecklist::getOverallProgress($item->id));


            }



            $records[$key] = $item;

        }

//        dd($records);


        return  view('onboard.employee.index',compact(['list','records']));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
        $response = OnboardingEmployeeChecklist::factoryCreate();
        return redirect()->back()->with([
            'message'=>'Feedback sent, personnel notified.',
            'error'=>false
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OnboardingEmployeeChecklist  $onboardingEmployeeChecklist
     * @return \Illuminate\Http\Response
     */
    public function show(OnboardingEmployeeChecklist $onboardingEmployeeChecklist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OnboardingEmployeeChecklist  $onboardingEmployeeChecklist
     * @return \Illuminate\Http\Response
     */
    public function edit(OnboardingEmployeeChecklist $onboardingEmployeeChecklist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OnboardingEmployeeChecklist  $onboardingEmployeeChecklist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $response = OnboardingEmployeeChecklist::factoryUpdate($id);

        return redirect()->back()->with([
            'message'=>'Feedback updated , assigned personnel notified',
            'error'=>false
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OnboardingEmployeeChecklist  $onboardingEmployeeChecklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(OnboardingEmployeeChecklist $onboardingEmployeeChecklist)
    {
        //
    }

    function staffOnboard(){

        if (request()->filled('employee_id') && User::userIdExists(request('employee_id'))){

            return $this->userOnboard(request('employee_id'));

        }

        return redirect()->back()->with([
            'message'=>'Invalid employee-selected',
            'error'=>true
        ]);

    }

    function userOnboard($userId){
        $parent_id = 0;
        $parent = null;
        $authUserId = \Illuminate\Support\Facades\Auth::user()->id;

        if (request()->filled('parent_id')){
            $parent_id = request('parent_id');

            $parent = OnboardingChecklist::fetchv2()->where('id',$parent_id)->first();
//            dd($parent,$parent_id);
            if (is_null($parent)){
                return redirect()->back()->with([
                    'message'=>'Invalid URL!',
                    'error'=>true
                ]);
            }


        }

        //\App\User::fetch()->where('id',request('employee_id'))->first()->email
        $employee_email = 'N/A';
        if (!request()->filled('employee_id')){
            return redirect()->back()->with([
                'message'=>'Invalid employee!',
                'error'=>true
            ]);
        }
        $employee_email = User::fetch()->where('id',request('employee_id'))->first()->email;
        $has_parent_request = request()->filled('parent_id');

        $list = OnboardingChecklist::getParentList($parent_id)->get();
        $list = collect($list);
        $list = $list->map(function($item) use ($userId,$authUserId){

            $validAccessors = [$item->assigned_personnel->id,$userId];

            $item->can_post = in_array($authUserId,$validAccessors);

            $item->document_template_is_empty = empty($item->document_template);
            $item->has_completed_previous_step = OnboardingEmployeeChecklist::hasCompletedPreviousStep($userId,$item->id);
            $item->has_child_steps = OnboardingChecklist::hasChildSteps($item->id);
            $item->user_has_submitted_feedback = OnboardingEmployeeChecklist::userHasSubmittedFeedback($item->id,$userId);
            $approved = OnboardingEmployeeChecklist::getEntry($item->id,$userId,'status');
            $item->approved = $approved;
            $item->is_approved = ($item->approved == 1);
            $item->approval_status = OnboardingEmployeeChecklist::getEntry($item->id,$userId,'status') == 1? 'Approved' : 'Pending';
            $item->personnel_name = $item->assigned_personnel->name;
            $item->can_approve = OnboardingChecklist::canApprove($item->id,\Illuminate\Support\Facades\Auth::user()->id);
            $item->comments = OnboardingEmployeeChecklist::getEntry($item->id,$userId,'comments');
            $item->comment_supervisor = OnboardingEmployeeChecklist::getEntry($item->id,$userId,'comment_supervisor');
            $item->support_document = OnboardingEmployeeChecklist::getEntry($item->id,$userId,'support_document');
            $item->has_support_document = !empty($item->support_document);
            $authId = \Illuminate\Support\Facades\Auth::user()->id;
            $item->can_reply_checklist = $item->user_has_submitted_feedback && $item->can_approve; // && ($authId == $userId)) || ;

            $item->employee_checklist_id = OnboardingEmployeeChecklist::getEntry($item->id,$userId,'id');


            return $item;

        });

//        $percentage_progress = OnboardingChecklist::getOverallProgress($item->id);




        $percentage_progress = 0;

        if (OnboardingEmployeeChecklist::currentStage($userId)->exists()){

            $percentage_progress = OnboardingChecklist::getOverallProgress($userId);

        }


        return view('onboard.employee.start',compact(['list','parent_id','parent','userId','employee_email','has_parent_request','percentage_progress']));
    }

    function selfOnboard(){

        return $this->userOnboard(request('employee_id'));
        //Auth::user()->id

    }


}
