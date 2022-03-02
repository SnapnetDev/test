<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntegrationPolicy extends Model
{
    protected $fillable=['hcrecruit_url','hcrecruit_app_key','app_key','company_id'];
}
