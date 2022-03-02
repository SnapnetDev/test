<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    protected $fillable=['id','user_id','current_medical_conditions',
        'past_medical_conditions','surgeries_hospitalizations','medications','medication_allergies',
        'family_history','social_history','others'];

    protected $casts=[
        'current_medical_conditions'=>'array',
        'past_medical_conditions'=>'array',
        'surgeries_hospitalizations'=>'array',
        'medications'=>'array',
        'medication_allergies'=>'array',
        'family_history'=>'array',
        'social_history'=>'array',
        'others'=>'array'
    ];
}
