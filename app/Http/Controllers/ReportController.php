<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
// use App\Traits\Micellenous;


class ReportController extends Controller
{
    // use Micellenous;
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('microsoft');
    }


    //UPSTREAM REPORT
    public function index()
    {
        return view('report.upstream.index');
    }




    //POWER BI Quick insight
    public function getReport(Request $request)
    {




        $page=$request->page;
        $accessToken=$this->plugPowerBI();

        $dataSetId=env('QI_DATASETID','');

        $group_ids = "813c11e1-bdcd-4538-95da-ebb415e4d94e";

        //UPSTREAM
        if($request->page=='demographics')
        {

            $groupId = $group_ids;            $reportId="f3d608be-7f64-48aa-80ea-5069ddc995ca";
        }
        elseif($request->page=='hr')
        {
            $groupId = $group_ids;           $reportId="163e6140-d20a-4e93-a7ab-8fff78ec0200";
        }

        elseif($request->page=='job_roles')
        {
            $groupId= $group_ids;            $reportId="14c8852a-9873-4fe0-b9a8-efe9b0850a3a";
        }

        elseif($request->page=='loan_request')
        {
            $groupId= $group_ids;            $reportId="a45f0d58-c96c-409f-9b32-25e8558848cd";
        }

        elseif($request->page=='payroll')
        {
            $groupId= $group_ids;            $reportId="010043d3-f7a4-4b06-a670-37b8e5ccddf1";
        }





        elseif($request->page=='quickinsight')
        {
            $accessToken=$this->QuickInsight();            $groupId=env('QI_GROUPID','');
        }

                        $user=\Auth::user();
                                if(\Auth::user()->role->permissions->contains('constant', 'group_access')){
                                    $companies=companies();
                                    $filtered = collect($companies)->map(function ($company, $key)  use($user){
                                        if($company->id==8 && $user->company_id==8){
                                            return $company->id;
                                        }elseif($company->id!=8){
                                            return  $company->id;
                                        }
                                    });
                                    $filtered=$filtered->all();
                                }else{
                                    $filtered=[$user->company_id];
                                }


        return view('executiveview.index', compact('accessToken', 'groupId', 'reportId', 'dataSetId', 'page','filtered'));
    }

    public function QuickInsight(){
        $groupId=env('QI_GROUPID','');
        $dataSetId=env('QI_DATASETID','');
        $response = \Curl::to("https://api.powerbi.com/v1.0/myorg/groups/$groupId/datasets/$dataSetId/GenerateToken")
            ->withHeader('Authorization:Bearer '.$this->plugPowerBI())
            ->withData(['accessLevel'=>'view'])
            ->asJson()
            ->post();
// 572f20f0-947c-42b4-a2e4-2faa5a18a786/datasets/d8340c85-6c25-43c6-b1fd-9198f48b9403
        // dd($response);
        return $response->token;


    }

    public function plugPowerBI(){

        $auth_data= [ 'grant_type'=>'password',
            'client_id'=>env('POW_CLIENT_ID','') ,
            'client_secret'=> env('POW_CLIENT_SECRET',''),
            'resource'=>'https://analysis.windows.net/powerbi/api',
            'username'=> env('POW_USERNAME',''),
            'password'=> env('POW_PASSWORD',''),
            'scope'=>'openid'

        ];
        // if(session()->has('access_token') && session('access_token')!=''){
        //         return session('access_token');
        //     }
        $response = \Curl::to('https://login.microsoftonline.com/snapnet.com.ng/oauth2/token')
            ->withData($auth_data)
            ->post();
        $response= json_decode($response);
        // dd($response);
        if(!isset($response->access_token)){
            \Auth::logout();
            return 'Error';
        }
        session(['access_token'=>$response->access_token]);
        return $response->access_token;
    }



}


