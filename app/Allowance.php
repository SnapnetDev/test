<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    protected $fillable = ['name','location'];
    private $change = 'test';
}
