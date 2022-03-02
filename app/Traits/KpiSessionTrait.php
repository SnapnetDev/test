<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 5/17/2020
 * Time: 7:18 PM
 */

namespace App\Traits;


use App\KpiInterval;
use App\KpiSession;
use App\Notifications\HrGeneralNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait KpiSessionTrait
{

	function makeCurrent(){
//		$this->resetOthers();
		$year = date('Y');
        $obj = KpiSession::where('year',$year)->first();

        if (is_null($obj)){
          $obj = new KpiSession;
          $obj->year = $year;
        }

		$obj->kpi_interval_id = request()->get('kpi_interval_id');

        $obj->save();

        return [
        	'message'=>'Selected interval activated',
	        'error'=>false
        ];

	}

	function getCurrentInterval(){
	  $year = date('Y');
      $obj = KpiSession::where('year',$year)->first();
      if (is_null($obj)){
      	return 'No - Interval Selected';
      }else{
      	return $obj->interval->name . ' (' . $obj->year . ')'; // . ' - Selected '
      }
	}

	function getCurrentIntervalObject(){
		$year = date('Y');
		$obj = KpiSession::where('year',$year)->first();
		return $obj;
	}

	function getIntervalCollection(){
		$year = date('Y');

		$query = (new KpiInterval)->newQuery();

		$query = $query->whereHas('year',function (Builder $builder) use ($year){
			return $builder->where('year',$year);
		});

		return $query->get();

	}


	function getCurrentIntervalId(){
		$year = date('Y');
		$obj = KpiSession::where('year',$year)->first();
		if (is_null($obj)){
			return 0;
		}else{
			return $obj->interval->id;
		}
	}

	function hasInterval(){
		return $this->getCurrentIntervalId() != 0;
	}

	function emailIsValid($email){
		$r = explode('@', $email);
		if (count($r) > 1){
			$r = $r[1];
			$r = explode('.', $r);
			if (count($r) > 1){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function sendGeneralNotification(){
		$allUsers = User::where('locked',0)->get();
//		$allUsers = User::where('emp_num',76)->get();

		try{
			foreach ($allUsers as $user){
				if ($this->emailIsValid($user->email))$user->notify(new HrGeneralNotification());
			}

			return [
				'message'=>'Notifications sent to all employees',
				'error'=>false
			];
		}catch (\Exception $exception){
			return [
				'message'=>'Please configure your SMTP server!!!' . $exception->getMessage(),
				'error'=>true
			];
		}

	}




	function intervalIsAboutToClose(){
	    $now = date('Y-m-d');
	    $kpi_interval_id = (new KpiSession)->getCurrentIntervalObject()->kpi_interval_id;
	    $kpiIntervalObj = KpiInterval::find($kpi_interval_id);

	    if (is_null($kpiIntervalObj)){
	        return false;
        }
//	    dd($kpiIntervalObj);
	    //interval_stop
        $fromDate = Carbon::createFromFormat('Y-m-d', $now);
        $toDate = Carbon::createFromFormat('Y-m-d',$kpiIntervalObj->interval_stop);
        $days = $fromDate->diffInDays($toDate);
//        dd($days);
        return ($days <= 3);
    }




}