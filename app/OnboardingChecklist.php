<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OnboardingChecklist extends Model
{
    //

    private $parentId = null;
    private $lastStep = 0;

    public $message = '';
    public $error = false;

    function uploadTemplate(){
        $name = 'document_template';
        if (request()->file($name)){
//            if (empty($path)){
                $path = $name;
//            }
            $image = request()->file($name)->store($path,['disk'=>'uploads']);
            $this->$name = $image;
        }
//        dd($image);

//        dd($name);

        return $this;
    }

    function getLastStep(){

        $query = (new OnboardingChecklist)->newQuery();

        if (!empty($this->parentId)){

            $query = $query->where('parent_id',$this->parentId);
            $query = $query->orderBy('step','desc');
            $first = $query->first();

            if ($first){
                $this->lastStep = $first->step;
            }

            return  $this;

        }

        $query = $query->orderBy('step','desc');
        $first = $query->first();

//        $this->lastStep = 0;
        if ($first){
            $this->lastStep = $first->step;
        }

        return $this;
    }

    function useParentId($id=null){
        $this->parentId = $id;
        return $this;
    }

    function validateAssignedPersonnel(){
        $id = request('assigned_personnel_id');
        $query = (new User)->newQuery();
        $query = $query->where('id',$id);
//        dd($query->first());
        if (!$query->exists()){
            throw new \Exception('Invalid personnel!');
        }
    }

    function assigned_personnel(){
        return $this->belongsTo(User::class,'assigned_personnel_id')->withDefault([
            'first_name'=>'Not Assigned',
            'last_name'=>'Not Assigned'
        ]);
    }

    static function getAssignedPersonnel($checklistId){
      //assigned_personnel
//        dd(self::getOne($checklistId)->exists(),$checklistId);
        if (!self::getOne($checklistId)->exists()){
            $obj = new \stdClass;
            $obj->first_name = 'Not Assigned1';
            $obj->last_name = 'Not Assigned';
            return $obj;
        }
        if (!self::getOne($checklistId)->whereHas('assigned_personnel',function(Builder $builder){
            return $builder;
        })->exists()){
            $obj = new \stdClass;
            $obj->first_name = 'Not Assigned';
            $obj->last_name = 'Not Assigned';
            return $obj;
        }
      $data = self::getOne($checklistId)->first();
//        dd($data->assigned_personnel);
      return $data->assigned_personnel;
    }

    function garbage(){
        $this->delete();
    }

    function getDocumentTemplateLinkAttribute(){
        if (!empty($this->document_template)){
            return '<a href="' . asset('uploads/' . $this->document_template) . '">Download Template</a>';
        }

        return 'N/A';
    }

    static function getCheckListTitle($parent_id=''){
        $query = (new OnboardingChecklist)->newQuery();
        $query = $query->where('id',$parent_id);
        if ($query->exists()){

            return '<b>(' . $query->first()->action . ')</b>';

        }

        return  '';

    }


    function saveNewCheckList(){

        $this->step  = $this->lastStep + 1;
        $this->action = request('action');
        $this->assigned_personnel_id = request('assigned_personnel_id');
        $this->time = request('time');
        $this->duration = request('duration');
        $this->parent_id = $this->parentId;
//        dd($this->parent_id);
        $this->save();

        $this->message = 'New checklist added';
        $this->error = false;

    }

    function saveCheckList(){
//        dd(request()->all());
//        $this->step  = $this->lastStep + 1;
        $this->action = request('action');
        $this->assigned_personnel_id = request('assigned_personnel_id');
        $this->time = request('time');
        $this->duration = request('duration');
        $this->uploadTemplate();
//        $this->parent_id = $this->parentId;
        $this->save();

        $this->message = 'Checklist saved';
        $this->error = false;
        return $this;
    }

    static function getPersonnels($email=''){
        return (new User)->newQuery()->when(!empty($email),function(Builder $builder) use ($email){
            return $builder->where('email','like','%' . $email . '%');
        })->get();
    }

    static function fetch(){

        $query = (new self)->newQuery();
        $query = $query->when(request()->filled('parent_id'),function(Builder $builder){
            return $builder->where('parent_id',request('parent_id'));
        });

        return $query;

    }

    static function fetchv2(){
        return (new self)->newQuery();
    }

    static function getOne($id){
        return self::fetchv2()->where('id',$id);
    }

    static function getTotalSteps($checkListId){

        $data = self::fetch()->where('id',$checkListId)->first();

        if ($data){

            $parent_id = $data->parent_id;
            return self::fetch()->where('parent_id',$parent_id)->count();

        }

        return 0;

    }

//    static function parent($checklistId){
//        return self::fetchv2()->where('id',$checklistId)->where('parent_id','>',0);
//    }

    static function topParents(){
       return self::fetchv2()->where('parent_id',0);
    }

    static function getOverallProgress($userId){

        $list = collect(self::topParents()->get());
        $total = self::topParents()->count();

//        dd($list);

        $list = $list->filter(function($item) use ($userId){
//            dd(self::getProgress($item->id,$userId));
            if (self::hasChildSteps($item->id)){
//                dd(self::getProgress($item->id,$userId) == 100);
                return (self::getProgress($item->id,$userId) == 100);
            }
//            dd(OnboardingEmployeeChecklist::getFilled($item->id,$userId)->where('status',1)->exists());
            return OnboardingEmployeeChecklist::getFilled($item->id,$userId)->where('status',1)->exists();

        });

//        dd($list->count());

        return (int) ($list->count()/$total * 100);

//        foreach ($list as $item){
//            $progress = self::getProgress($item->id,$userId);
//        }

    }

    static function getChildProgress($checkListId,$userId){

            $total = self::getChildren($checkListId)->count();

            $userFilledCount = OnboardingEmployeeChecklist::getFilledInTermsOfParent($checkListId,$userId)->count();

            return  (int)($userFilledCount/$total * 100);

    }

    static function getProgress($checkListId,$userId){

        if (self::hasChildSteps($checkListId)){

//            dd(0);
            return self::getChildProgress($checkListId,$userId);

        }

        if (!self::getOne($checkListId)->exists()){
//            dd(1);
            return 0;
        }

        $checkList = self::getOne($checkListId)->first();

        //$total = self::getChildren($checkList->parent_id)->count();

        $filledByEmployee = OnboardingEmployeeChecklist::getFilled($checkListId,$userId);

        if ($filledByEmployee->where('status',1)->exists()){
            $step = $checkList->step;
            $total = self::getChildren($checkList->parent_id)->count();
            return  (int) ($step/$total * 100);
        }

//        dd('last');

        return 0;

    }

    static function hasCompletedChildChecklist($checkListId,$userId){

        $list = self::fetch()->where('parent_id',$checkListId)->get();
        $totalCount = self::fetch()->where('parent_id',$checkListId)->count();

        $list = collect($list);
        $countSoFar = 0;

        $list = $list->each(function($item) use (&$countSoFar,$userId){
             if (OnboardingEmployeeChecklist::hasCompletedCurrentStep($userId,$item->id)){
                $countSoFar+=1;
             }
        });

        return ( $countSoFar == $totalCount );  //((int) $countSoFar/$totalCount) * 100;

    }


    static function getParentList($parentId){
        return self::fetch()->where('parent_id',$parentId);
    }

    static function getChildren($checklistId){
        return self::fetchv2()->where('parent_id',$checklistId);
    }

    static function getChildSteps($checkListId){
        return self::fetch()->where('parent_id',$checkListId);
    }

    static function hasChildSteps($checkListId){
        return self::getChildren($checkListId)->exists();
//        return self::fetch()->where('parent_id',$checkListId)->exists();
    }

    static function getPrevStep($checklistId){

        $data = self::fetch()->where('id',$checklistId)->first();
        $prevStep = $data->step - 1;
        $parentId = $data->parent_id;

        $data = self::fetch()->where('parent_id',$parentId)->where('step',$prevStep)->first();

        return $data;

    }

    function parent(){
        return $this->belongsTo(OnboardingChecklist::class,'parent_id');
    }

    static function canApprove($checklistId,$userId){

        return self::fetchv2()->where('id',$checklistId)->whereHas('assigned_personnel',function (Builder $builder) use ($userId){
            return $builder->where('id',$userId);
        })->exists();
    }




}
