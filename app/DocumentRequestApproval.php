<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentRequestApproval extends Model
{
    protected $fillable=['document_request_id','stage_id','approver_id','comments','status'];
    public function document_request()
    {
        return $this->belongsTo('App\DocumentRequest','document_request_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\User','approver_id');
    }
    public function stage()
    {
        return $this->belongsTo('App\Stage','stage_id');
    }
}
