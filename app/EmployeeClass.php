<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeClass extends Model
{
    protected $table="employee_classes";
    protected $fillable=['name'];

    public function users()
    {
    	return $this->hasMany('App\User');
    }
}
