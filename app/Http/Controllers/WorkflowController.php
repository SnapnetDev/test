<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\UserGroup;
use App\Workflow;
use App\Stage;
use App\Filters\WorkflowFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use RuntimeException;


class WorkflowController extends Controller
{


  public function __construct()
  {
      $this->middleware('auth');
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Bugsnag::notifyException(new RuntimeException("Test error"));
        
        try {
          if (count($request->all())==0) {
          $stages=Stage::all();
          $workflows=Workflow::withCount('stages')->orderByDesc('created_at')->paginate(5);
          return view('workflows.list',['workflows'=>$workflows,'stages'=>$stages]);
        }else {
          $stages=Stage::all();
            $workflows=WorkflowFilter::apply($request);
            // return $users;
            return view('workflows.list',['workflows'=>$workflows,'stages'=>$stages]);
        }
        } catch (\Exception $e) {
          return  $e;
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
          $users=User::all();
          $roles=Role::all();
          $groups=UserGroup::all();
          return view('workflows.create',compact('users','roles','groups'));
        } catch (\Exception $e) {

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try {
          $this->validate($request, ['name'=>'required']);
          if ($request->input('stagename')!==null) {
              $no_of_stages=count($request->input('stagename'));
           }

           if ($request->input('user_id')!==null) {
              $no_of_users=count($request->input('user_id'));
           }
          if ($request->input('role')!==null) {
           $no_of_roles=count($request->input('role'));
         }
         if ($request->input('group')!==null) {
           $no_of_groups=count($request->input('group'));
         }
           $no_of_users_used=0;
           $no_of_roles_used=0;
           $no_of_groups_used=0;
        $wf=Workflow::create(['name'=>$request->name]);

          if($no_of_stages>0){
          for ($i=0; $i <$no_of_stages ; $i++) {
            if ($request->type[$i]==1) {
             $stage=$wf->stages()->create(['name'=>$request->stagename[$i],'position'=>$i,'user_id'=>$request->user_id[$no_of_users_used],'type'=>$request->type[$i]]);
             $no_of_users_used++;
            }elseif ($request->type[$i]==2) {
              $stage=$wf->stages()->create(['name'=>$request->stagename[$i],'position'=>$i,'type'=>$request->type[$i],'role_id'=>$request->role[$no_of_roles_used]]);
              $no_of_roles_used++;
            }elseif($request->type[$i]==3){
              $stage=$wf->stages()->create(['name'=>$request->stagename[$i],'position'=>$i,'type'=>$request->type[$i],'group_id'=>$request->group[$no_of_groups_used]]);
              $no_of_groups_used++;
            }
             }
            }
          // $wf= new Workflow();
          // $wf->name =$request->name;
          // $wf->save();

          // $logmsg='Workflow was created.';

          // $no_of_stages=count($request->input('stagename'));
          // for ($i=0; $i < $no_of_stages; $i++) {
          //   $stg=new Stage();
          //   $stg->name=$request->stagename[$i];
          //   $stg->position=$i;
          //   $stg->user_id=$request->user_id[$i];
          //   $stg->type=$request->type[$i];
          //   $stg->role=$request->role[$i];
          //   $stg->workflow_id=$wf->id;
          //   $stg->save();
          //   $logmsg='stage was created and added to ';

          // }

          return redirect()->route('workflows')->with(['success'=>'Workflow Created Successfully']);
        // } catch (\Exception $e) {

        // }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      try {
        $wf=Workflow::find($id);
        return view('workflows.view',['workflow'=>$wf]);
      } catch (\Exception $e) {

      }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
              $users=User::all();
              $roles=Role::all();
          $groups=UserGroup::all();
            $workflow=Workflow::find($id);
            return view('workflows.edit',compact('workflow','users','roles','groups'));
        } catch (\Exception $e) {

        }

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
      try {
        $wf= Workflow::find($id);
        //  if ($wf->payrolls ||$wf->document_requests||$wf->employee_reimbursements||$wf->separations||$wf->leave_requests||$wf->loan_requests) {
        //  return redirect()->route('workflows.edit',$id)->with('error', 'Workflow is in use, Therefore changes cannot be made to the workflow');
    //   }else{
        $this->validate($request, ['name'=>'required']);
        $wf= Workflow::find($id);
        $wf->name =$request->name;
        $wf->save();
        // $wf->stages()->detach();
        $removed_stages=\App\Stage::whereNotIn('id',$request->stage_id)->where('workflow_id',$wf->id)->delete();
        
             $no_of_users_used=0;
             $no_of_roles_used=0;
             $no_of_groups_used=0;
        $no_of_stages=count($request->input('stagename'));
             if($no_of_stages>0){
                 for ($i=0; $i <$no_of_stages ; $i++) {
                     $stg=@Stage::find($request->stage_id[$i]);
                     if ($request->type[$i]==1) {

                        
                        if($stg){
                            $stg->update(['name'=>$request->stagename[$i],'position'=>$i,'user_id'=>$request->user_id[$no_of_users_used],'type'=>$request->type[$i],'workflow_id'=>$wf->id]);
                        }else{
                            $stage=$wf->stages()->create(['name'=>$request->stagename[$i],'position'=>$i,'user_id'=>$request->user_id[$no_of_users_used],'type'=>$request->type[$i]]);
                        }
                         $no_of_users_used++;
                     }elseif ($request->type[$i]==2) {
                          if($stg){
                            $stg->update(['name'=>$request->stagename[$i],'position'=>$i,'role_id'=>$request->role[$no_of_roles_used],'type'=>$request->type[$i],'workflow_id'=>$wf->id]);
                        }else{
                            $stage=$wf->stages()->create(['name'=>$request->stagename[$i],'position'=>$i,'type'=>$request->type[$i],'role_id'=>$request->role[$no_of_roles_used]]);
                        }
                         
                         $no_of_roles_used++;
                     }elseif($request->type[$i]==3){
                          if($stg){
                            $stg->update(['name'=>$request->stagename[$i],'position'=>$i,'group_id'=>$request->group[$no_of_groups_used],'type'=>$request->type[$i],'workflow_id'=>$wf->id]);
                        }else{
                           $stage=$wf->stages()->create(['name'=>$request->stagename[$i],'position'=>$i,'type'=>$request->type[$i],'group_id'=>$request->group[$no_of_groups_used]]); 
                        }
                         
                         
                         $no_of_groups_used++;
                     }
                 }
             }
//        for ($i=0; $i < $no_of_stages; $i++) {
//          $stg=Stage::find($request->stage_id[$i]);
//          if($stg){
//
//            $stg->name=$request->stagename[$i];
//            $stg->position=$i;
//            $stg->user_id=$request->user_id[$i];
//            $stg->type=$request->type[$i];
//            $stg->role_id=$request->role[$i];
//             $stg->save();
//          }else{
//            $stg=new Stage();
//            $stg->name=$request->stagename[$i];
//            $stg->position=$i;
//            $stg->user_id=$request->user_id[$i];
//            $stg->type=$request->type[$i];
//            $stg->role_id=$request->role[$i];
//            $stg->workflow_id=$wf->id;
//             $stg->save();
//          }
//
//      }

        return redirect()->route('workflows')->with(['success'=>'Changes saved Successfully']);
    //   }
      } catch (\Exception $e) {
        return $e->getMessage();
      }
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
    public function hasactivereviews($id)
    {
      $has_reviews=DB::table('document_reviews')
      ->join('stages', 'document_reviews.stage_id', '=', 'stages.id')
      ->where('document_reviews.status', 0)
      ->where('stages.workflow_id', $id)->exists();
      return $has_reviews;
    }
    public function alterStatus(Request $request)
    {

      $wf=Workflow::find($request->id);
      if ($request->status=='true') {
        $wf->status=1;
        $wf->save();
        $logmsg='Workflow status was changed from inactive to active.';

        return response()->json('enabled',200);
      }elseif($request->status=='false')
      {
        $wf->status=0;
        $wf->save();
        $logmsg='Workflowstatus was changed from active to inactive';

        return response()->json('disabled',200);
      }


    }

}
