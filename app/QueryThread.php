<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueryThread extends Model
{
    //
    protected $fillable=['queried_user_id','num_of_reminders','query_type_id','parent_id','content','created_by','status','action_taken','effective_date'];


    public function querytype(){

        return $this->belongsTo('\App\Query','query_type_id')->withDefault();
    }
   public function getStatusColorAttribute(){
        return $this->status=='open' ? 'info' : 'success';
   }
    public function querieduser(){
        return $this->belongsTo('App\User','queried_user_id')->withDefault();
    }
    public function createdby(){
        return $this->belongsTo('App\User','created_by')->withDefault();
    }
}
