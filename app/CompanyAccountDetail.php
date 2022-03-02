<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyAccountDetail extends Model
{
    protected $table='companyaccountdetails';
    protected $fillable=['accountNum','first_name','last_name','bank_id'];
}
