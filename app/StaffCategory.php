<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffCategory extends Model
{
    protected $fillable = ['name'];
    public function users()
    {
        return $this->hasMany('App\User','staff_category_id');
    }
}
