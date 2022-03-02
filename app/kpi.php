<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class kpi extends Model
{
    //
    protected $fillable=['deliverable','targetweight','targetamount','status','quarter','comment','created_by','approved','approval_id','reason','assigned_to'];

    public function kpiassignedto(){
    	return $this->hasMany('App\kpiassignedto','kpi_id');
    }
    public function kpicomment(){
    	return $this->hasMany('App\kpicomment','kpi_id');
    }

    public function user(){
    	return $this->belongsTo('App\User','created_by');	
    }

    public function getApprovedStatusAttribute(){
    	$approved=$this->approved;
    	if($approved==0){
    		return ['warning',"Pending"];
    	}
    	if($approved==1){
    		return ['success',"Approved",$this->getApproverNameAttribute()];
    	}
    	if($approved==2){
    		return ['danger',"Rejected",$this->getApproverNameAttribute()];
    	}
    }

    public  function getHtmlStatusAttribute(){
        if($this->status==0){
            return "<span class='tag tag-warning'>In-progress </span>";
        }
        elseif($this->status==1){
           return "<span class='tag tag-warning'>In-progress</span>";
       }
       else{

           return "<span class='tag tag-success'>Completed</span>";
       }

   }
 
   public function getAchievedAttribute(){

       $achievedamount= \App\ProgressReport::where('kpiid',$this->id)
                                    ->selectRaw('SUM(achievedamount) as achievedtodate')
                                    ->value('achievedtodate');
                  return round($achievedamount);
   }

   public function progressreport(){
    return $this->hasMany('App\ProgressReport','kpiid');
}

public function getAssignedtoEmailAttribute(){
    $approved=$this->assigned_to;
    return \App\User::where('id',$approved)->value('email');

}

public function getApproverNameAttribute(){
   $approved=$this->approval_id;
   return \App\User::where('id',$approved)->value('name');
}
}
