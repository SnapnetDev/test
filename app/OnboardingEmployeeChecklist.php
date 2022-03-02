<?php

namespace App;

use App\Mail\Onboard_NotifyEmplyeeOfUpdatedChecklistByPersonnel;
use App\Mail\Onboard_NotifyPersonnelOfUpdatedChecklist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Self_;
use Auth;

class OnboardingEmployeeChecklist extends Model
{
    //

    function checklist(){
        return $this->belongsTo(OnboardingChecklist::class,'onboarding_check_list_id');
    }

    static function currentStage($userId){
        return self::fetch()->where('employee_id',$userId)->orderBy('id','desc');
    }

    static function getCurrentStage($userId){

        $data = self::currentStage($userId)->first(); //  self::fetch()->where('employee_id',$userId)->orderBy('id','desc')->first();

        if ($data){

            return [
                'action'=>$data->checklist->action,
                'step'=> 'Stage:' . $data->checklist->step . ' ' . $data->checklist->action,
                'document'=>$data->support_document,
                'confirmed_by'=>'N/A',
                'status'=>0,
                'progress'=>OnboardingChecklist::getProgress($data->checklist->id),
                'handler'=>$data->checklist->assigned_personnel->name
            ];

        }


        return [
            'action'=>'N/A',
            'step'=>'N/A',
            'document'=>'',
            'confirmed_by'=>'N/A',
            'status'=>0,
            'progress'=>0,
            'handler'=>'N/A'
        ];

    }

    static function getCurrentHandler(){

    }

    static function getCurrentUploadedDocument(){

    }

    static function fetch(){
        return (new self)->newQuery();
    }

    static function hasStartedStep($userId,$checkListId){
        return self::fetch()->where('employee_id',$userId)->where('onboarding_check_list_id',$checkListId)->exists();
    }

//    static function hasCompletedStep($userId,$checkListId){
//        return self::fetch()
//            ->where('employee_id',$userId)
//            ->where('onboarding_check_list_id',$checkListId)
//            ->where('status',1)
//            ->exists();
//    }


    static function hasCompletedPreviousStep($userId,$checklistId){

        $data = OnboardingChecklist::getPrevStep($checklistId);

        if (is_null($data)){
           return true;
        }

        return self::hasCompletedCurrentStep($userId,$data->id);

    }

    static function getFilledList($checkListId){

        $obj = OnboardingChecklist::fetchv2()->where('id',$checkListId)->first();
        $parent_id = $obj->parent_id;

        return self::fetch()->whereHas('checklist',function(Builder $builder) use ($parent_id){

            return $builder->where('parent_id',$parent_id);

        });

    }

    static function hasCompletedCurrentStep($userId,$checkListId):bool{

        //
        if (OnboardingChecklist::hasChildSteps($checkListId)){

//            dd('child');
            $count = OnboardingChecklist::getChildSteps($checkListId)->count();
            $list = collect(OnboardingChecklist::getChildSteps($checkListId)->get());
            $check = 0;

            $list->each(function($item) use (&$check,$userId){
//                dd($item);
                if (self::hasCompletedCurrentStep($userId,$item->id)){
//                    dd('yes');
                    $check+=1;
                }
            });

//            dd($count,$check);

            return ($count == $check);
        }


        return self::fetch()
            ->where('onboarding_check_list_id',$checkListId)
            ->where('employee_id',$userId)
            ->where('status',1)->exists();

    }


    function withUpload(){

        if (!OnboardingChecklist::canApprove($this->onboarding_check_list_id,Auth::user()->id)){
            $name = 'support_document';
            if (request()->file($name)){
//            if (empty($path)){
                $path = $name;
//            }
                $image = request()->file($name)->store($path,['disk'=>'uploads']);
                $this->$name = $image;
            }
            return;
        }

    }

    static function employeeIsPosting():bool{
      return request('employee_id') == \Illuminate\Support\Facades\Auth::user()->id;
    }

    static function personnelIsPosting():bool{
       $personnelId = \Illuminate\Support\Facades\Auth::user()->id;
       $onboarding_check_list_id = request('onboarding_check_list_id');
       return OnboardingChecklist::fetch()
           ->where('id',$onboarding_check_list_id)
           ->where('assigned_personnel_id',$personnelId)->exists();
    }

    function createEmployeeChecklistFeedback():OnboardingEmployeeChecklist{

        $this->employee_id = request('employee_id');
        $this->onboarding_check_list_id = request('onboarding_check_list_id');
        $this->comments = request('comments');
        $this->status = 0;
        $this->withUpload();
        $this->save();

        if (self::employeeIsPosting()){
            self::notifyPersonnelOfUpdatedCheckList($this);
        }

        if (self::personnelIsPosting()){
            self::notifyEmployeeOfUpdatedCheckListByPersonnel($this);
        }
        return $this;
    }

    static function notifyPersonnelOfUpdatedCheckList(OnboardingEmployeeChecklist $checklist){
        try {
            $user = User::fetch()->where('id',$checklist->employee_id)->first();
            $personnel = $checklist->checklist->assigned_personnel;
            Mail::to([$personnel->email])->send(new Onboard_NotifyPersonnelOfUpdatedChecklist($user,$personnel,$checklist));
        }catch (\Exception $exception){
            //check mail settings...
        }
    }

    static function notifyEmployeeOfUpdatedCheckListByPersonnel(OnboardingEmployeeChecklist $checklist){
        try {
            $user = User::fetch()->where('id',$checklist->employee_id)->first();
            $personnel = $checklist->checklist->assigned_personnel;
            Mail::to([$user->email])->send(new Onboard_NotifyEmplyeeOfUpdatedChecklistByPersonnel ($user,$personnel,$checklist));
        }catch (\Exception $exception){
            //check mail settings...
        }
    }

    function handleUpdateStatus(){

        if (!OnboardingChecklist::canApprove($this->onboarding_check_list_id,Auth::user()->id)){
            return;
        }

        if (request()->filled('status')){
            $this->status = request('status');
        }

    }

    function updateComments(){

        if (!OnboardingChecklist::canApprove($this->onboarding_check_list_id,Auth::user()->id)){
            $this->comments = request('comments');
            return;
        }

        $this->comment_supervisor = request('comment_supervisor');

    }


    function updateEmployeeCheckListFeedback(){
        $this->withUpload();

        $this->handleUpdateStatus();
        $this->updateComments();
        $this->save();

        if (self::employeeIsPosting()){
            self::notifyPersonnelOfUpdatedCheckList($this);
        }

        if (self::personnelIsPosting()){
            self::notifyEmployeeOfUpdatedCheckListByPersonnel($this);
        }

        return $this;
    }

    static function factoryCreate(){
        return (new self)->createEmployeeChecklistFeedback();
    }

    static function factoryUpdate($id){
        return self::fetch()->where('id',$id)->first()->updateEmployeeCheckListFeedback();
    }

    static function getEntry($checkListId,$userId,$col){
        if (self::fetch()->where('employee_id',$userId)->where('onboarding_check_list_id',$checkListId)->exists()){
          return self::fetch()->where('employee_id',$userId)->where('onboarding_check_list_id',$checkListId)->first()->$col;
        }
        return '';
    }

    static function userHasSubmittedFeedback($checkListId,$userId):bool{
        return self::fetch()->where('employee_id',$userId)->where('onboarding_check_list_id',$checkListId)->exists();
    }


    static function getFilledInTermsOfParent($checklistId,$userId){
        return self::fetch()->whereHas('checklist',function(Builder $builder) use ($checklistId){

            return $builder->where('parent_id',$checklistId);

        })->where('employee_id',$userId)->where('status',1);
    }

    static function getCheckListFilledByEmployeeStatus($checklistId,$userId){

    }

    static function getFilled($checklist,$userId){
        return self::fetch()->where('onboarding_check_list_id',$checklist)->where('employee_id',$userId);
    }





}
