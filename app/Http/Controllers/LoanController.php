<?php

namespace App\Http\Controllers;

use App\LoanType;
use Illuminate\Http\Request;
use App\Traits\LoanTrait;
use App\Traits\PayrollTrait;
use App\User;
use App\LoanRequest;
use App\Setting;
use Auth;

class LoanController extends Controller
{
	use LoanTrait,PayrollTrait;
   public function index()
   {


   	if(count(Auth::user()->promotionHistories) >0){
//   	$netpay=$this->getUserNetPay(Auth::user()->id);
//   	$maximum_allowed=$netpay*(intval(Setting::where('name','loan_allowed')->first()->value)/100);
//   	$annual_interest=intval(Setting::where('name','loan_interest')->first()->value);
   	$loanTypes = LoanType::all();

   	//'maximum_allowed','annual_interest',

	    //'netpay'

   	    return view('loan.index',compact('loanTypes'));

	  }else{

   		return 'Please set grade for user';

	  }



   }
   public function store(Request $request)
    {
        //

        try {

            return $this->processPostLoan($request);

        }catch (\Exception $exception){

            return $exception->getMessage();

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        return $this->processGetLoan($id,$request);
    }
}
