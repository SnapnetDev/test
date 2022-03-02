<?php

namespace App\Http\Controllers;

use App\JobAva;
use App\KpiAgreement;
use App\KpiData;
use App\KpiInterval;
use App\KpiSession;
use App\KpiUserScore;
use App\KpiYear;
use App\User;
use Illuminate\Http\Request;

class ImportUserFromJSONController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //

	    return view('import_json.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    	if (!request()->filled('type')){
    		return redirect()->back()->with([
    			'message'=>'Type required!'
		    ]);
	    }
	    $type = request('type');


    	if ($type == 'user'){

		    $json = '/json/1608638019_user.json'; //public_path('/json/1608638019_user.json');
		    return (new User)->importFromJSON($json);
	    }


	    if ($type == 'job_ava'){
		    $json = '/json/1608638019_job_ava.json'; //public_path('/json/1608638019_user.json');
		    return (new JobAva)->importFromJSON($json);
	    }

	    if ($type == 'kpi_agreement'){
		    $json = '/json/1608638019_kpi_agreement.json'; //public_path('/json/1608638019_user.json');
		    return (new KpiAgreement)->importFromJSON($json);
	    }

	    if ($type == 'kpi_data'){
		    $json = '/json/1608638019_kpi_data.json'; //public_path('/json/1608638019_user.json');
		    return (new KpiData)->importFromJSON($json);
	    }

	    if ($type == 'kpi_interval'){
		    $json = '/json/1608638019_kpi_interval.json'; //public_path('/json/1608638019_user.json');
		    return (new KpiInterval)->importFromJSON($json);
	    }

	    if ($type == 'kpi_session'){
		    $json = '/json/1608638019_kpi_session.json'; //public_path('/json/1608638019_user.json');
		    return (new KpiSession)->importFromJSON($json);
	    }

	    if ($type == 'kpi_user_score'){
		    $json = '/json/1608638019_kpi_user_score.json'; //public_path('/json/1608638019_user.json');
		    return (new KpiUserScore)->importFromJSON($json);
	    }

	    if ($type == 'kpi_year'){
		    $json = '/json/1608638019_kpi_year.json'; //public_path('/json/1608638019_user.json');
		    return (new KpiYear)->importFromJSON($json);
	    }
        //
	    //ping-user

//	    $hnd = fopen($json, 'r+');
//	    $data = fread($hnd, filesize($json) );
//
//	    fclose($hnd);
//
//	    dd(json_decode($data));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
