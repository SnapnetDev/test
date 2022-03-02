<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfirmationRequirement extends Model
{
    protected $fillable = ['name','compulsory','is_approval_requirement'];
}
