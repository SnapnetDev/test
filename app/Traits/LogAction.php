<?php
namespace App\Traits;

use App\AuditLog;
/**
 *
 */
trait LogAction
{
  function saveLog($level,$type,$type_id,$route,$message,$created_by)
  {
    $auditLog = AuditLog::create(['level' => $level,'auditable_type'=>$type,'auditable_id'=>$type_id,'route'=>$route,'message'=>$message,'created_by'=>$created_by]);
    return true;
  }
}
