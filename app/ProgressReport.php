<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgressReport extends Model
{
    //
    protected $table='progress_reports';
    protected $fillable=['id', 'created_at', 'updated_at', 'report', 'from', 'to', 'achievedamount', 'achievedtodate', 'reportcomment', 'status', 'emp_id', 'kpiid']; 

    public function kpi(){
    	return $this->belongsTo('App\kpi','kpiid')->withDefault();
    }

    public function user(){

    	return $this->belongsTo('App\User','emp_id')->withDefault();
    }

   public  function getResolveStatusAttribute(){
        if($this->status==0){
            return "<span class='tag tag-warning'>In-progress </span>";
        }
        elseif($key==1){
             return "<span class='tag tag-warning'>In-progress</span>";
        }
        else{

             return "<span class='tag tag-success'>Completed</span>";
        }

}

}
