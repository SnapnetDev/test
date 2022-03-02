<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSection extends Model
{
    protected $fillable=['name','company_id','other_name','salary_project_code','charge_project_code'];
    protected $table='user_sections';

    public function users()
    {
    	return $this->hasMany('App\User','section_id');
    }



}
