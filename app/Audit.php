<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
  protected $fillable = ['level','auditable_type','auditable_id','route','message','created_by'];

    public function auditable()
    {
        return $this->morphTo();
    }
    public function user()
    {
      return $this->belongsTo('App\User','created_by');
    }
}
