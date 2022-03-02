<?php


namespace App\Traits;



use App\CompanyOrganogram;
use App\CompanyOrganogramLevel;
use App\CompanyOrganogramPosition;
use App\Notifications\ApproveDocumentRequest;
use App\Notifications\DocumentRequestApproved;
use App\Notifications\DocumentRequestPassedStage;
use App\Notifications\DocumentRequestRejected;
use App\Notifications\DocumentRequestUploaded;
use App\Stage;
use App\User;
use App\Workflow;
use Illuminate\Http\Request;
use App\DocumentRequest;
use App\DocumentRequestApproval;
use App\DocumentRequestType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait OrganogramTrait

{
    public function processGet($route,Request $request)
    {
        switch ($route) {

            case 'settings':
                # code...
                return $this->settings($request);
                break;
            case 'get_organogram':
                # code...
                return $this->get_organogram($request);
                break;
            case 'delete_organogram':
                # code...
                return $this->delete_organogram($request);
                break;
            case 'organogram_positions':
                # code...
                return $this->organogram_positions($request);
                break;
            case 'get_organogram_position':
                # code...
                return $this->get_organogram_position($request);
                break;
            case 'view_organogram_positions':
                # code...
                return $this->view_organogram_positions($request);
                break;
            case 'delete_organogram_position':
                # code...
                return $this->delete_organogram_position($request);
                break;
            case 'get_organogram_level':
                # code...
                return $this->get_organogram_level($request);
                break;
            case 'delete_organogram_level':
                # code...
                return $this->delete_organogram_level($request);
                break;
            default:
                # code...
                break;

        }
    }

    public function processPost(Request $request){
        // try{
        switch ($request->type) {
            case 'save_organogram':
                # code...
                return $this->save_organogram($request);
                break;
            case 'save_organogram_position':
                # code...
                return $this->save_organogram_position($request);
                break;
            case 'save_organogram_level':
                # code...
                return $this->save_organogram_level($request);
                break;



            default:
                # code...
                break;
        }

    }


    public function settings(Request $request){
        if (Auth::user()->role->permissions->contains('constant', 'manage_organogram')){
        $organograms=CompanyOrganogram::where('company_id',companyId())->get();
        $organogram_levels=CompanyOrganogramLevel::where('company_id',companyId())->get();
        $users=User::where('status','!=',2)->withoutGlobalScopes()->get();


        return view('organogram.settings',compact('organograms','organogram_levels','users'));
        }else{
            return redirect()->back();
        }

    }
    public function get_organogram(Request $request){
        return $organogram=CompanyOrganogram::find($request->organogram_id);
    }
    public function delete_organogram(Request $request){
    $organogram=CompanyOrganogram::find($request->organogram_id);
    if ($organogram){

        $organogram->delete();
        return 'success';
    }
}
    public function save_organogram(Request $request){
        CompanyOrganogram::updateOrCreate(['id'=>$request->organogram_id],['name'=>$request->name,'company_id'=>companyId(),'manager_id'=>$request->manager_id,'updated_by'=>Auth::user()->id]);
        return 'success';
    }
    public function get_organogram_level(Request $request){
        return $organogram_level=CompanyOrganogramLevel::find($request->organogram_level_id);
    }
    public function delete_organogram_level(Request $request){
        $organogram_level=CompanyOrganogramLevel::find($request->organogram_level_id);
        if ($organogram_level){

            $organogram_level->delete();
            return 'success';
        }
    }
    public function save_organogram_level(Request $request){
        CompanyOrganogramLevel::updateOrCreate(['id'=>$request->organogram_level_id],['name'=>$request->name,'company_id'=>companyId(),'updated_by'=>Auth::user()->id]);
        return 'success';
    }
    public function organogram_positions(Request $request){
        $organogram=CompanyOrganogram::find($request->organogram_id);
        $organogram_positions=CompanyOrganogramPosition::where('company_organogram_id',$organogram->id)->get();
        $organogram_levels=CompanyOrganogramLevel::where('company_id',companyId())->get();
        $users=User::where('status','!=',2)->withoutGlobalScopes()->get();

        return view('organogram.positions',compact('organogram_positions','organogram','organogram_levels','users'));

    }
    public function view_organogram_positions(Request $request){
        $organogram=CompanyOrganogram::find($request->organogram_id);
        $organogram_positions=CompanyOrganogramPosition::where('company_organogram_id',$organogram->id)->get();
        $organogram_levels=CompanyOrganogramLevel::where('company_id',companyId())->get();
        $users=User::where('status','!=',2)->withoutGlobalScopes()->get();
        return view('organogram.indexjs',compact('organogram_positions','organogram','organogram_levels','users'));

    }
    public function get_organogram_position(Request $request){
        return $organogram_position=CompanyOrganogramPosition::find($request->company_organogram_position_id);
    }
    public function delete_organogram_position(Request $request){
        $organogram_position=CompanyOrganogramPosition::find($request->company_organogram_position_id);
        if ($organogram_position){

            $organogram_position->delete();
            return 'success';
        }
    }
    public function save_organogram_position(Request $request){
        CompanyOrganogramPosition::updateOrCreate(['id'=>$request->company_organogram_position_id],['company_organogram_id'=>$request->company_organogram_id,
            'company_organogram_level_id'=>$request->company_organogram_level_id,'user_id'=>$request->user_id,'name'=>$request->name,
            'p_id'=>$request->p_id,'sp_id'=>$request->sp_id,'company_id'=>companyId(),'updated_by'=>Auth::user()->id]);
        return 'success';
    }





}
