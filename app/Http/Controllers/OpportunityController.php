<?php

namespace App\Http\Controllers;

use App\Client;
use App\Commission;
use App\Opportunity;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Auth;

class OpportunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:manage_user','auth']);
    }

    public function index()
    {
        $clients=Client::all();
        $opportunities = Opportunity::withCount('commissions')->get();//Trophy Cassava
        return view('commissions.opportunities', compact('opportunities','clients'));
    }

    public function showOpportunity($id)
    {
        $staffs = User::all();
        $opportunity = Opportunity::findorfail($id);
        $staff_commissions = Commission::where('opportunity_id', $id)->with('user')->get();
        return view('commissions.staffOpportunity', compact('staff_commissions', 'opportunity', 'staffs'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //return $request->all();
        Validator::make($request->all(), [
            'client_id' => 'required',
            'project_name' => 'required',
            'project_amount' => 'required',
            'project_status' => 'required',
            'payment_status' => 'required',
            'date' => 'required',
        ])->validate();

        if ($request->has('id')){
            return $this->updateOpportunity($request);
        }

        $date=Carbon::createFromFormat('m/d/Y',$request->date)->format('Y-m-d');
        $new = new Opportunity();
        $new->client_id = $request->client_id;
        $new->project_name = $request->project_name;
        $new->project_amount = $request->project_amount;
        $new->project_status = $request->project_status;
        $new->payment_status = $request->payment_status;
        $new->date = $date;
        $new->save();
        return ['status'=>'success','details'=>'successfully added Opportunity'];
        return Redirect::back();
    }

    public function storefromExcel(Request $request)
    {
        Validator::make($request->all(), [
            'client_id' => 'required',
            'project_name' => 'required',
            'date' => 'required',
        ])->validate();

        $new = new Opportunity();
        $new->client_id = $request->client_id;
        $new->project_name = $request->project_name;
        $new->date = $request->date;
        $new->project_status = $request->project_status;
        $new->save();
        return \redirect(route('opportunity.index'));
    }

    public function updateOpportunity($request){
        $new = Opportunity::find($request->id);
        $new->client_id = $request->client_id;
        $new->project_name = $request->project_name;
        $new->date = Carbon::parse($request->date)->format('Y-m-d');
        $new->project_status = $request->project_status;
        $new->save();
        return ['status'=>'success','details'=>'successfully updated Opportunity'];
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        return $opportunity=Opportunity::where('id',$id)->first();
        return view('commissions.opportunities', compact('opportunity'));
    }


    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'client_id' => 'required',
            'project_name' => 'required',
            'date' => 'required',
        ])->validate();

        $new = Opportunity::find($id);
        $new->client_id = $request->client_id;
        $new->project_name = $request->project_name;
        $new->date = $request->date;
        $new->project_status = $request->project_status;
        $new->save();
        return \redirect(route('opportunity.index'));
    }

    public function destroy($id)
    {
        //
    }

    public function downloadTemplate(){
        $view = 'commissions.excelopportunitytemplate';
        $name='Opportunity Template';
        return \Excel::create($name, function ($excel) use ($view,$name) {
            $excel->sheet("Sheet 1", function ($sheet) use ($view) {
                $sheet->loadView("$view")
                    ->setOrientation('landscape');
            });

        })->export('xlsx');
    }

    public function excelImport(Request $request){

        if ($request->hasFile('template')){
            try{
                $rows = \Excel::load($request->template)->get();
                if ($rows) {
                    //return $rows;
                    foreach ($rows as $key => $row) {
                        $new = new Opportunity();
                        $new->client_id = $row['client'];
                        $new->project_name = $row['project_name'];
                        $new->project_amount = $row['project_amount'];
                        $new->project_status = $row['project_status'];
                        $new->payment_status = $row['payment_status'];
                        $new->date = $row['project_date'];
                        $new->save();
                    }
                    return ['status'=> 'success','details'=>'Successfully uploaded details'];
                }
            }
            catch(\Exception $ex){
                return ['status'=> 'error','details'=>'Error uploading'];
            }
        }

        return ['status'=> 'error','details'=>'Error uploading'];

    }
}
