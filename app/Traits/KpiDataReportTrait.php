<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 6/21/2020
 * Time: 9:52 PM
 */

namespace App\Traits;


use App\KpiData;
use App\KpiSession;
use App\KpiUserScore;
use App\User;
use Illuminate\Database\Eloquent\Builder;

trait KpiDataReportTrait
{

    private $avgUser = 0;
    private $avgLineManager= 0;
    private $avgHr = 0;
    private $totalCount = 0;

    private function attachUserScore($data,$userId){
        foreach ($data as $k=>$datum){
            $userScore = (new KpiUserScore)->fetch([
                'user_id'=>$userId,
                'kpi_data_id'=>$datum->id
            ])->first();

           if (is_null($userScore)){
               $userScore = new \stdClass;
               $userScore->user_score = 0;
               $userScore->user_comment = 'N/A';
               $userScore->manager_score = 0;
               $userScore->manager_comment = 'N/A';
               $userScore->hr_score = 0;
               $userScore->hr_comment = 'N/A';
           }

           $data[$k]->userScore = $userScore;

           $this->totalCount+=1;
           $this->avgUser+=($userScore->user_score * 25/100);
           $this->avgLineManager+=($userScore->manager_score * 50/100);
           $this->avgHr+=($userScore->hr_score * 25/100);
        }

        return $data;
    }

	function getUserReport($userId,$kpiIntervalId){
//        $userScore = (new KpiUserScore)->fetch([
//            'user_id'=>$user_id,
//            'kpi_data_id'=>$kpi_data_id
//        ])->first();


	    $data = [];

	    $data['interval'] = (new KpiSession)->getCurrentInterval();

	    $userObj = User::find($userId);

        $workdept_id = $userObj->job_id;


        $data['department_list'] = (new KpiData)->fetch([
            'type'=>'dep',
            'kpi_interval_id'=>$kpiIntervalId,
            'dep_id'=>$workdept_id,
            'scope'=>'public'
        ])->get();

        $data['department_list'] = $this->attachUserScore($data['department_list'],$userId);

        $query = (new KpiData)->fetch([
	        'type'=>'dep',
	        'kpi_interval_id'=>$kpiIntervalId,
	        'dep_id'=>$workdept_id,
	        'kpi_user_score_user_id'=>$userId,
	        'scope'=>'private'
        ]);

        $data['individual_department_list'] = $query->get();
        $data['has_individual_department_list'] = $query->exists();



        //kpi_user_score_user_id
        $data['individual_department_list'] = $this->attachUserScore($data['individual_department_list'],$userId);


        $data['organisation_list'] = (new KpiData)->fetch([
            'type'=>'org',
            'kpi_interval_id'=>$kpiIntervalId,
            'dep_id'=>$workdept_id,
            'scope'=>'public'
        ])->get();
        $data['organisation_list'] = $this->attachUserScore($data['organisation_list'],$userId);


        $query = (new KpiData)->fetch([
	        'type'=>'org',
	        'kpi_interval_id'=>$kpiIntervalId,
	        'dep_id'=>$workdept_id,
	        'kpi_user_score_user_id'=>$userId,
	        'scope'=>'private'
        ]);

        $data['individual_organisation_list'] = $query->get();
        $data['has_individual_organisation_list'] = $query->exists();
        $data['individual_organisation_list'] = $this->attachUserScore($data['individual_organisation_list'],$userId);

        $data['avgUser'] = $this->avgUser;
        $data['avgLineManager'] = $this->avgLineManager;
        $data['avgHr'] = $this->avgHr;
        $data['totalCount'] = $this->totalCount;


        return $data;

    }

	function userHasCompletedEvaluation(){
		
	}

}