<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentRequestType extends Model
{
    protected $fillable=['name','created_by','workflow_id','company_id'];
    public function document_requests()
    {
        return $this->hasMany('App\DocumentRequest','document_request_type_id');
    }
    public function workflow()
    {
        return $this->belongsTo('App\Workflow','workflow_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User','created_by');
    }
}
