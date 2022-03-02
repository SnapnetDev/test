<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    protected $fillable = ['type'];

    public function users()
    {
        return $this->hasMany('App\User','employee_type_id');
    }
}
