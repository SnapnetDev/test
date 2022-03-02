<?php

namespace App\Http\Controllers;

use App\MedicalHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MedicalHistoryController extends Controller
{
    public function formats(){
        $currentMedicalConditions=[
            ['id'=>'','name'=>'','date'=>'2020-04-22'],
            ['id'=>'','name'=>'','date'=>'2020-04-22'],
            ['id'=>'','name'=>'','date'=>'2020-04-22'],
            ['id'=>'','name'=>'','date'=>'2020-04-22'],
        ];

        $pastMedicalConditions=[
            ['id'=>'','name'=>'','date'=>'2020-04-22'],
            ['id'=>'','name'=>'','date'=>'2020-04-22'],
            ['id'=>'','name'=>'','date'=>'2020-04-22'],
            ['id'=>'','name'=>'','date'=>'2020-04-22'],
        ];

        $surgeriesHospitalizations=[
            ['id'=>'','name'=>'','date'=>'2020-04-22'],
            ['id'=>'','name'=>'','date'=>'2020-04-22'],
            ['id'=>'','name'=>'','date'=>'2020-04-22'],
            ['id'=>'','name'=>'','date'=>'2020-04-22'],
        ];

        $medications=[
            'Med 1','Med 2','Med 3',
        ];

        $medication_allergies=[
            'Med 1','Med 2','Med 3',
        ];

        $family_history=[
            ['id'=>'','name'=>'','member'=>''],
            ['id'=>'','name'=>'','member'=>''],
        ];
        $social_history=[
            'do_you_smoke'=>'no',
            'cigarettes_per_day'=>0,
            'cigarettes_per_week'=>0,
            'alcoholic_drinks_per_day'=>0,
            'alcoholic_drinks_per_week'=>0,
            'illegal_drugs'=>'no',
            'minutes_of_exercise_per_day'=>0,
            'exercise_days_week'=>0,
            'hours_of_television_per_day'=>0,
            'fast_food_per_week'=>0,
        ];
        $others=[

        ];


    }

    public function store(Request $request){
        //return $request->social_history;
        if ($request->task=='delete'){
            return $this->handleDelete($request);
        }
        if ($request->type=='cmc'){
            $date=Carbon::parse($request->date)->format('Y-m-d');
            $this->saveCurrentMedicalCondition($request->name,$date);
        }
        elseif ($request->type=='pmc'){
            $date=Carbon::parse($request->date)->format('Y-m-d');
            $this->savePastMedicalCondition($request->name,$date);
        }
        elseif ($request->type=='sh'){
            $date=Carbon::parse($request->date)->format('Y-m-d');
            $this->saveSurgeries($request->name,$date);
        }
        elseif ($request->type=='med'){
            $this->saveMedication($request->name);
        }
        elseif ($request->type=='medall'){
            $this->saveMedicationAllergies($request->name);
        }
        elseif ($request->type=='fam'){
            $this->saveFamilyHistory($request->name,$request->member);
        }
        elseif ($request->type=='so'){
            $data=$request->social_history;
            $this->saveSocialHistory($data);
        }
        return 'success';
    }

    public function handleDelete($request){
        if ($request->type=='cmc'){
            $medical_history=$this->ensureRecordExists();
            $cmc=$medical_history->current_medical_conditions;
            $collection=collect($cmc);
            $id=$request->id;
            $filtered = $collection->filter(function ($value, $key) use($id) {
                return $value['id'] != $id;
            });
            $new= collect($filtered->all());
            MedicalHistory::where('id',$medical_history->id)->update(['current_medical_conditions'=>$new]);
        }
        elseif ($request->type=='pmc'){
            $medical_history=$this->ensureRecordExists();
            $pmc=$medical_history->past_medical_conditions;
            $collection=collect($pmc);
            $id=$request->id;
            $filtered = $collection->filter(function ($value, $key) use($id) {
                return $value['id'] != $id;
            });
            $new= collect($filtered->all());
            MedicalHistory::where('id',$medical_history->id)->update(['past_medical_conditions'=>$new]);

        }
        elseif ($request->type=='sh'){
            $medical_history=$this->ensureRecordExists();
            $sh=$medical_history->surgeries_hospitalizations;
            $collection=collect($sh);
            $id=$request->id;
            $filtered = $collection->filter(function ($value, $key) use($id) {
                return $value['id'] != $id;
            });
            $new= collect($filtered->all());
            MedicalHistory::where('id',$medical_history->id)->update(['surgeries_hospitalizations'=>$new]);
        }
        elseif ($request->type=='med'){
            $medical_history=$this->ensureRecordExists();
            $med=$medical_history->medications;
            $collection=collect($med);
            $id=$request->id;
            $filtered = $collection->filter(function ($value, $key) use($id) {
                return $value != $id;
            });
            $new= collect($filtered->all());
            MedicalHistory::where('id',$medical_history->id)->update(['medications'=>$new]);
        }
        elseif ($request->type=='medall'){
            $medical_history=$this->ensureRecordExists();
            $medall=$medical_history->medication_allergies;
            $collection=collect($medall);
            $id=$request->id;
            $filtered = $collection->filter(function ($value, $key) use($id) {
                return $value != $id;
            });
            $new= collect($filtered->all());
            MedicalHistory::where('id',$medical_history->id)->update(['medication_allergies'=>$new]);
        }
        elseif ($request->type=='fam'){
            $medical_history=$this->ensureRecordExists();
            $fam=$medical_history->family_history;
            $collection=collect($fam);
            $id=$request->id;
            $filtered = $collection->filter(function ($value, $key) use($id) {
                return $value['id'] != $id;
            });
            $new= collect($filtered->all());
            MedicalHistory::where('id',$medical_history->id)->update(['family_history'=>$new]);
        }
        return 'success';
    }
    private function saveCurrentMedicalCondition($name,$date){
        $medical_history=$this->ensureRecordExists();
        $cmc=$medical_history->current_medical_conditions;
        $cmc[]=['id'=>mt_rand(1,999999),'name'=>$name,'date'=>$date];
        $cmc=collect($cmc);
        MedicalHistory::where('id',$medical_history->id)->update(['current_medical_conditions'=>$cmc]);
    }
    private function savePastMedicalCondition($name,$date){
        $medical_history=$this->ensureRecordExists();
        $pmc=$medical_history->past_medical_conditions;
        $pmc[]=['id'=>mt_rand(1,999999),'name'=>$name,'date'=>$date];
        $pmc=collect($pmc);
        MedicalHistory::where('id',$medical_history->id)->update(['past_medical_conditions'=>$pmc]);
    }
    private function saveSurgeries($name,$date){
        $medical_history=$this->ensureRecordExists();
        $sh=$medical_history->surgeries_hospitalizations;
        $sh[]=['id'=>mt_rand(1,999999),'name'=>$name,'date'=>$date];
        $sh=collect($sh);
        MedicalHistory::where('id',$medical_history->id)->update(['surgeries_hospitalizations'=>$sh]);
    }
    private function saveMedication($name){
        $medical_history=$this->ensureRecordExists();
        $med=$medical_history->medications;
        $med[]=$name;
        $med=collect($med);
        MedicalHistory::where('id',$medical_history->id)->update(['medications'=>$med]);
    }
    private function saveMedicationAllergies($name){
        $medical_history=$this->ensureRecordExists();
        $medall=$medical_history->medication_allergies;
        $medall[]=$name;
        $medall=collect($medall);
        MedicalHistory::where('id',$medical_history->id)->update(['medication_allergies'=>$medall]);
    }
    private function saveFamilyHistory($name,$member){
        $medical_history=$this->ensureRecordExists();
        $fam=$medical_history->family_history;
        $fam[]=['id'=>mt_rand(1,999999),'name'=>$name,'member'=>$member];
        $fam=collect($fam);
        MedicalHistory::where('id',$medical_history->id)->update(['family_history'=>$fam]);
    }
    private function saveSocialHistory($data){
        $medical_history=$this->ensureRecordExists();
        $old_so=$medical_history->social_history;
        $data=collect($data);
        MedicalHistory::where('id',$medical_history->id)->update(['social_history'=>$data]);
    }

    private function ensureRecordExists(){
        $social_history=[
            'do_you_smoke'=>'no',
            'cigarettes_per_day'=>0,
            'cigarettes_per_week'=>0,
            'alcoholic_drinks_per_day'=>0,
            'alcoholic_drinks_per_week'=>0,
            'illegal_drugs'=>'no',
            'minutes_of_exercise_per_day'=>0,
            'exercise_days_week'=>0,
            'hours_of_television_per_day'=>0,
            'fast_food_per_week'=>0,
        ];
       return $medical_history = MedicalHistory::firstOrCreate(
            ['user_id' => Auth::id()],
            ['current_medical_conditions'=>[],
                'past_medical_conditions'=>[],
                'surgeries_hospitalizations'=>[],
                'medications'=>[],
                'medication_allergies'=>[],
                'family_history'=>[],
                'social_history'=>$social_history,
                'others'=>[]]
        );
    }


}
