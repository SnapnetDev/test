<?php

namespace App\Http\Controllers;

use App\SharesAllocation;
use App\SharesVested;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SharesAllocationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:manage_user','auth'])->except(['getDetails','endofYearShares','myShares']);
    }

    public function index(Request $request)
    {
        $today = Carbon::today();
        \App\SharesVested::where('status','pending')->whereDate('date_vested','<=',$today)->update(['status'=>'vested']);
        $shares=SharesAllocation::with('shares_vested')->get();
        $users=User::all();
        $sh=SharesVested::all();
        $total=$sh->sum('no_of_shares');
        $pending=$sh->where('status','pending')->sum('no_of_shares');
        $vested=$sh->where('status','vested')->sum('no_of_shares');

        $users2=User::all();
        $users=User::with('myshares')->with('shares_allocations')->whereHas('shares_allocations')->get();
        foreach ($users as $user){
            $myshares=$user->myshares;
            $myallocations=$user->shares_allocations;
            $user['allshares']=$myallocations->sum('no_of_shares');
            $user['allshares2']=$myshares->sum('no_of_shares');
            $user['pendings']=$myshares->where('status','pending')->sum('no_of_shares');
            $user['vesteds']=$myshares->where('status','vested')->sum('no_of_shares');
        }

        if (isset($request->date)){
            $date=$request->date;
            $search_title='Search Result for '.$date;
            $shares=SharesAllocation::whereYear('start_date',$date)->with('shares_vested')->get();
        }
        return view('shares.sharesreport',compact('shares','users','total','pending','vested','date','search_title','users2'));
    }

    public function vest_breakdown($id){
        $today=Carbon::today();
        SharesVested::where('id',$id)->update(['status'=>'vested','date_vested'=>$today]);
        return redirect()->back();
    }

    public function revoke_share($id){
        SharesVested::where('id',$id)->update(['status'=>'withdrawn']);
        return redirect()->back();
    }

    public function getDetails($id){
        $breakdowns=SharesVested::where('shares_allocation_id',$id)->get();
        return view('shares.partials.breakdown',compact('breakdowns'));
    }

    public function excelImport(Request $request){

        if ($request->hasFile('template')){
            try{
                $rows = Excel::load($request->template)->get();
                if ($rows) {
                    //return $rows;
                    foreach ($rows as $key => $row) {
                        $user=User::where('emp_num',$row['empnum'])->first();
                        if ($user){
                            $today=Carbon::today();
                            //$date=Carbon::createFromFormat('m/d/Y',$row['start_date'])->format('Y-m-d');
                            //$date_use=Carbon::createFromFormat('m/d/Y',$row['start_date']);
                            $new = new SharesAllocation();
                            $new->user_id = $user->id;
                            $new->no_of_shares = $row['no_of_shares'];
                            $new->years_vested = $row['vest_years'];
                            $new->start_date = $row['start_date'];
                            $new->save();
                            //return $new;
                            if ($new->years_vested==0){
                                $nn=new SharesVested();
                                $nn->shares_allocation_id=$new->id;
                                $nn->no_of_shares=$new->no_of_shares;
                                $nn->date_vested=$new->start_date;
                                $nn->status='vested';
                                $nn->save();
                            }
                            else{
                                for ($i=1; $i<=$new->years_vested; $i++){
                                    $nn=new SharesVested();
                                    $nn->shares_allocation_id=$new->id;
                                    $nn->no_of_shares=$new->no_of_shares/$new->years_vested;
                                    $nn->date_vested=$new->start_date->addYear()->format('Y-m-d');
                                    if ($nn->date_vested<=$today){$status='vested';}
                                    else{$status='pending';}
                                    $nn->status=$status;
                                    $nn->save();
                                }
                            }

                        }
                    }
                    $request->session()->flash('success', 'Component added successfully!');
                }
            }
            catch(\Exception $ex){
                $request->session()->flash('Error', 'Please Check Your Excel file!');
            }
        }

    }


    public function excelTemplate(){
        $view = 'shares.exceltemplate';
        $name='Shares Allocation Template';
        return \Excel::create($name, function ($excel) use ($view,$name) {

            $excel->sheet("Sheet 1", function ($sheet) use ($view) {
                $sheet->loadView("$view")
                    ->setOrientation('landscape');
            });

        })->export('xlsx');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'no_of_shares' => 'required',
            'user_id' => 'required',
            'years_vested' => 'required',
            'date' => 'required',
        ])->validate();

        $today=Carbon::today();
        $date=Carbon::createFromFormat('m/d/Y',$request->date)->format('Y-m-d');
        $date_use=Carbon::createFromFormat('m/d/Y',$request->date);
        $new = new SharesAllocation();
        $new->user_id = $request->user_id;
        $new->no_of_shares = $request->no_of_shares;
        $new->years_vested = $request->years_vested;
        $new->start_date = $date;
        $new->save();

        if ($new->years_vested==0){
            $nn=new SharesVested();
            $nn->shares_allocation_id=$new->id;
            $nn->no_of_shares=$new->no_of_shares;
            $nn->date_vested=$date;
            $nn->status='vested';
            $nn->save();
        }
        else{
            for ($i=1; $i<=$new->years_vested; $i++){
                $nn=new SharesVested();
                $nn->shares_allocation_id=$new->id;
                $nn->no_of_shares=$new->no_of_shares/$new->years_vested;
                $nn->date_vested=$date_use->addYear()->format('Y-m-d');
                if ($nn->date_vested<=$today){$status='vested';}
                else{$status='pending';}
                $nn->status=$status;
                $nn->save();
            }
        }

        return redirect()->back();
    }
    public function endofYearShares(Request $request){
        if (isset($request->user_id) && isset($request->year)){
            $shares=SharesAllocation::where('user_id',$request->user_id)->with('shares_vested')->get();
            $shares_ids=$shares->pluck('id')->toArray();
            return $vested_end_of_year=SharesVested::whereIn('shares_allocation_id',$shares_ids)->whereYear('date_vested','<=',$request->year)->sum('no_of_shares');
        }
    }

    public function myShares(){
        $today = Carbon::today();
        \App\SharesVested::where('status','pending')->whereDate('date_vested','<=',$today)->update(['status'=>'vested']);
        $user=Auth::user();
        $year=Carbon::now()->year;
        $shares=SharesAllocation::where('user_id',$user->id)->with('shares_vested')->get();
        $shares_ids=$shares->pluck('id')->toArray();

        $vested_end_of_year=SharesVested::whereIn('shares_allocation_id',$shares_ids)->whereYear('date_vested','<=',$year)->sum('no_of_shares');
        $vested=$user->myshares->where('status','vested')->sum('no_of_shares');
        $pending=$user->myshares->where('status','pending')->sum('no_of_shares');
        $total=$user->myshares->sum('no_of_shares');
        return view('shares.myshares',compact('user','shares','vested','pending','total','year','vested_end_of_year'));
    }
    public function userShares($id){
        $user=User::findorfail($id);
        $year=Carbon::now()->year;
        $shares=SharesAllocation::where('user_id',$user->id)->with('shares_vested')->get();
        $shares_ids=$shares->pluck('id')->toArray();

        $vested_end_of_year=SharesVested::whereIn('shares_allocation_id',$shares_ids)->whereYear('date_vested','<=',$year)->sum('no_of_shares');
        $vested=$user->myshares->where('status','vested')->sum('no_of_shares');
        $pending=$user->myshares->where('status','pending')->sum('no_of_shares');
        $total=$user->myshares->sum('no_of_shares');
        return view('shares.myshares',compact('user','shares','vested','pending','total','year','vested_end_of_year'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
