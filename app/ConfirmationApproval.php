<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfirmationApproval extends Model
{
    protected $fillable = ['confirmation_id','stage_id','approver_id','comments','status'];
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\User','approver_id');
    }
    public function stage()
    {
        return $this->belongsTo('App\Stage','stage_id');
    }

    public function confirmation()
    {
        return $this->belongsTo('App\Confirmation','confirmation_id');
    }
}
