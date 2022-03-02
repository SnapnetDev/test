<?php

namespace App\Http\Controllers;

use App\Traits\MoodleTrait;
use Illuminate\Http\Request;
use App\Company;
use App\Department;
use App\Branch;
use App\Job;
use App\User;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class GlobalSettingController extends Controller
{

	use MoodleTrait;

	public function index($value='')
	{
//		echo 'cool';
		$this->handlePermissionMigration();
		return view('settings.index');

	}
	

}