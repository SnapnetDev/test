<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NPIndividualKPI extends Model
{
    //protected $fillable=['user_id', 'n_p_measurement_period_id', 'kpi_question','max_score','user_score','user_comment','manager_score','manager_comment'];
    protected $fillable=['n_p_user_id', 'kpi_question',
        'weight','target','target_words','actual','score','kpi_rating_type',
        'kpi_rating',
        'measurement','data_source','frequency_of_data','responsible_collation_unit',
        'user_comment','manager_comment'];

    public function np_user(){
        return $this->belongsTo('App\NPUser','n_p_user_id')->withDefault();
    }
}
