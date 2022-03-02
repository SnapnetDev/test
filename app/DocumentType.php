<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    //
    protected $fillable=['docname'];


    public function document(){
    	return $this->hasMany('App\Document','type_id');
    }
}

