<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class Document extends Model
{
    //
    protected   $fillable=[ 'document', 'type_id', 'user_id', 'last_mod_id', 'expiry','company_id'];
    protected $dates= ['expiry','created_at','updated_at'];


  protected static function boot()
    {
        parent::boot();
      
        static::addGlobalScope(new CompanyScope);
    }

    public function user(){
    	return $this->belongsTo('App\User','user_id')->withDefault();
    }
    public function user_modified(){
    	return $this->belongsTo('App\User','last_mod_id')->withDefault();
    }
   
    public function folder(){
    	return $this->belongsTo('App\DocumentType','type_id')->withDefault();
    }

    public function getdocumentNameAttribute(){
        $name=explode('/', $this->document);
        return isset($name[2]) ? $name[2] : $this->document ;
    }


}
