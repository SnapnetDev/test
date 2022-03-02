<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
 protected $fillable= ['name','position','user_id','type','role_id','group_id'];

  public function user()
  {
      return $this->belongsTo('App\User','user_id')->withDefault([
          'name' => 'No User',
      ]);
  }
  public function role()
  {
      return $this->belongsTo('App\Role','role_id')->withDefault([
          'name' => 'No Role',
      ]);
  }
  public function group()
  {
      return $this->belongsTo('App\UserGroup','group_id')->withDefault([
          'name' => 'No Group',
      ]);
  }
  public function workflow()
  {
      return $this->belongsTo('App\Workflow');
  }
  public function reviews()
  {
    return $this->hasMany('App\Stage');
  }
  public function audit_logs()
  {
      return $this->morphMany('App\AuditLog', 'auditable');
  }
}
