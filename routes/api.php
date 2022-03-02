<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/app_key', function (Request $request) {
    return env('APP_KEY');
});
Route::post('navsion_integration/','CompensationController@nav_int');
Route::post('hcrecruit_add_user/','UserController@saveNewHcRecruit');

Route::get('hcrecruit_companies/','UserController@getCompaniesForIntegration');
Route::get('hcrecruit_departments/{company_id}','UserController@getDepartmentsForIntegration');
Route::get('hcrecruit_branches/{company_id}','UserController@getBranchesForIntegration');
Route::get('hcrecruit_grades','UserController@getGradesForIntegration');
Route::get('hcrecruit_roles','UserController@getRolesForIntegration');
Route::get('hcrecruit_job_roles/{department_id}','UserController@getJobRolesForIntegration');


//biometric api
Route::get('/data', 'BiometricController@data');
Route::get('/iclock/cdata', 'BiometricController@checkDevice');
Route::post('/iclock/cdata', 'BiometricController@receiveRecords');
Route::get('/iclock/getrequest', 'BiometricController@getRequest');
Route::post('/iclock/devicecmd', 'BiometricController@deviceCMD');


Route::get('/iclock/getrequest', 'BiometricController@getrequest');
Route::get('/iclock/devicecmd', 'BiometricController@deviceCMD');

Route::get('/soft/auth', 'AttendanceAppController@authenticateUser');
Route::get('/soft/clock-in', 'AttendanceAppController@softClockIn');
Route::get('/soft/clock-out', 'AttendanceAppController@softClockOut');

Route::post('/soft/clock', 'AttendanceAppController@softClock');
Route::get('/soft/clock', 'AttendanceAppController@softClock');

//visitor
Route::get('/visitor/users', 'VisitorApiController@users');
Route::get('/visitor/departments', 'VisitorApiController@departments');
Route::get('/visitor/roles', 'VisitorApiController@roles');

//bc integration
Route::get('/bc-users', 'UserController@bc_export');
Route::get('/bc-payroll-journal', 'CompensationController@nav_int');

