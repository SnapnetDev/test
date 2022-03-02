<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\Http\Requests;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Curl;
use Carbon\Carbon;

class MicrosoftController extends Controller
{
    //redirect to p[rovider]
	 
	public function redirectToProvider(){
		
		$client_id=env('MICROSOFT_CLIENT_ID','');
		$redirect_uri=url('auth/microsoft/callback');//env('MICROSOFT_AUTH_URL','');
		
		return redirect("https://login.microsoftonline.com/common/oauth2/authorize?response_type=code&redirect_uri=$redirect_uri&client_id=$client_id");
	}
	
	public function getaccesstoken($granttype){
	  
	  $client_id=env('MICROSOFT_CLIENT_ID','');
		$redirect_uri=url('auth/microsoft/callback');//env('MICROSOFT_AUTH_URL','');
		$client_secret=env('MICROSOFT_CLIENT_SECRET','');
	  try{
	  $code=session('code');
	  if($granttype=='refresh_token'){
		 $response = Curl::to('https://login.microsoftonline.com/common/oauth2/token')
        ->withData([
		 'grant_type' => $granttype,
		 'redirect_uri'=>$redirect_uri,
		 'client_id'=>$client_id,
		 'client_secret'=>$client_secret,
		 'refresh_token'=>session('refreshtoken'),
		 'resource'=>'https://graph.microsoft.com/'
		 ])->post();  
	  }
	  else{
	  $response = Curl::to('https://login.microsoftonline.com/common/oauth2/token')
        ->withData([
		 'grant_type' => $granttype,
		 'redirect_uri'=>$redirect_uri,
		 'client_id'=>$client_id,
		 'client_secret'=>$client_secret,
		 'code'=>$code,
		 'resource'=>'https://graph.microsoft.com/'
		 ])->post();
	  } 
		$respone=json_decode($response);
	
		$accessToken=$respone->access_token;
		$refreshtoken=$respone->refresh_token;
		$notbeforetime=$respone->expires_on;
		session(['expirytime'=>$notbeforetime]);
		session(['refreshtoken'=>$refreshtoken]);
		session(['accesstoken'=>$accessToken]);
		 return;
	  }
	  catch(\Exception $ex){
		  
		  return redirect('/')->with('message','Some error occurred');
	  }
	}
	
	public function refresh_token(){
		
		if(session()->has('expirytime')){
		if(time()>=session('expirytime')){	
		$accessToken=self::getaccesstoken('refresh_token');
		}
		}
		
	}
	
	
	public function getuserdetails(){
			
		$graph = new Graph();
      
        $graph->setBaseUrl("https://graph.microsoft.com/")
		->setApiVersion("beta")
		->setAccessToken(session('accesstoken'));
		
	    $user = $graph->createRequest("get", "/me")
                 ->setReturnType(Model\User::class)
                ->execute();
		return $user;
	}
	
	
	   //get details from callback
	public function callbackurl(Request $request){
		
		try{
 
		if(\Auth::check()){
		    session(['microsoft'=>1]);
		    return redirect('home');
		    
		}
		session(['code'=>$request->code]);
		$accessToken=self::getaccesstoken('authorization_code');
		$user=self::getuserdetails();
		 //return 
	 
		$checkexist=\App\User::where(['email'=>$user->getMail(),'active'=>1])->select('id')->first();
		if($checkexist['id']==""){
		    
		 
	         return redirect('login')->with('message','You are not Autorized, Please contact Your Administrator');
		}
		
		//\App\User::where('email',$user->getMail())->update(['role'=>3]);
		 $getid=\App\User::where('email',$user->getMail())->select('id')->first();
		\Auth::loginUsingId($getid['id']);
		
		    session(['microsoft'=>1]);
		    $loginuser=\App\User::where('email',$user->getMail())->first();
$loginuser->update([
            'last_login_at' => Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp()
        ]);
	     return redirect('home');
		}
		catch(\Exception $ex){
			
			return redirect('/')->with('message','Some error occurred');
		}
	}
}
