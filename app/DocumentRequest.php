<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    protected $fillable=['title','document_request_type_id','due_date','file','user_id','workflow_id','status','company_id','comment'];
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope(new CompanyScope);
    // }

    public function document_request_type()
    {
        return $this->belongsTo('App\DocumentRequestType','document_request_type_id');
    }
    public function document_approvals()
    {
        return $this->hasMany('App\DocumentRequestApproval','document_request_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function workflow()
    {
        return $this->belongsTo('App\Workflow','workflow_id');
    }
}
