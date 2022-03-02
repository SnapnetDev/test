<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KpiAssessment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kpi_assessments';
    protected $fillable = ['user_id','fiscal_year','kpi_id','created_by','user_score','manager_score','company_score'];

    public function user()
	{
	    return $this->belongsTo('App\User', 'user_id');
	}
    public function kpi()
    {
        return $this->belongsTo('App\kpi', 'kpi_id');
    }
    
}