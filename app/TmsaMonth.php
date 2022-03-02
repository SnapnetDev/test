<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TmsaMonth extends Model
{
    protected $table='tmsa_months';
    protected $fillable=['name','month','year'];
    public function components()
    {
        return $this->belongsToMany('App\TmsaComponent','tmsa_component_month','tmsa_month_id','tmsa_component_id');
    }
}
