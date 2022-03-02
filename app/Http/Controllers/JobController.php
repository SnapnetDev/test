<?php

namespace App\Http\Controllers;

use App\Job;
use App\Department;
use App\Competency;
use App\Skill;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($department_id)
    {
        $department=Department::find($department_id);
        if ($department) {
          $department=Department::find($department_id);
        $jobs=Job::where('department_id',$department_id)->paginate(10);
        return view('jobs.list',compact('department','jobs'));
        }
         return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($department_id)
    {   if ($department_id) {
        $competencies=Competency::all();
        $department=Department::find($department_id);
        if ($department) {
           return view('jobs.create',compact('competencies','department'));
        }
             }
        
        return redirect()->back();
       
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
        if ($request->has('skill')) {
               $no_of_skills=count($request->input('skill'));
            }else{
                $no_of_skills=0;
            }
          
          $this->validate($request, ['title'=>'required|min:5']);
          $qualification=\App\Qualification::find($request->qualification);
        if (!$qualification) {
            $qualification=\App\Qualification::create(['name'=>$request->qualification]);
        }
          $job=Job::create(['title'=>$request->title,'description'=>$request->description,'parent_id'=>$request->parent_id,'department_id'=>$request->department_id,'personnel'=>$request->personnel,'qualification_id'=>$qualification->id]);
          $job->skills()->detach();
          // $logmsg='UserGroup created';
          // $this->saveLog('info','App\UserGroup',$group->id,'groups',$logmsg,Auth::user()->id);
          if($no_of_skills>0){
          for ($i=0; $i <$no_of_skills ; $i++) {
             $skill=Skill::where('id',$request->skill[$i])->orWhere('name','like','%'.$request->skill[$i])->first();
            if (!$skill) {
                $skill=Skill::create(['name'=>$request->skill[$i]]);
            }
            $job->skills()->attach($skill->id,['competency_id' => $request->competency_id[$i]]);
            // $user=User::find($request->user_id[$i]);
            // $logmsg='User was added to '.$group->name;
            // $this->saveLog('info','App\User',$user->id,'users',$logmsg,Auth::user()->id);
                }
          }
           return redirect()->route('job_list.view',['department_id'=>$request->department_id])->with(['success'=>'Changes Saved Successfully']); // } catch (\Exception $e) {

        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        $competencies=Competency::all();
        
        return view('jobs.edit',compact('competencies','job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
  
        
          if ($request->has('skill')) {
               $no_of_skills=count($request->input('skill'));
            }else{
                $no_of_skills=0;
            }
          // $this->validate($request, ['title'=>'required|min:5']);
         $qualification=\App\Qualification::find($request->qualification);
        if (!$qualification) {
            $qualification=\App\Qualification::create(['name'=>$request->qualification]);
        }
         $job->update(['title'=>$request->title,'description'=>$request->description,'parent_id'=>$request->parent_id,'personnel'=>$request->personnel,'qualification_id'=>$qualification->id]);
         
          $job->skills()->detach();
          // $logmsg='UserGroup created';
          // $this->saveLog('info','App\UserGroup',$group->id,'groups',$logmsg,Auth::user()->id);
          if($no_of_skills>0){
          for ($i=0; $i <$no_of_skills ; $i++) {
            $skill=Skill::where('id',$request->skill[$i])->orWhere('name','like','%'.$request->skill[$i])->first();
            if (!$skill) {
                $skill=Skill::create(['name'=>$request->skill[$i]]);
            }
            $job->skills()->attach($skill->id,['competency_id' => $request->competency_id[$i]]);
            // $user=User::find($request->user_id[$i]);
            // $logmsg='User was added to '.$group->name;
            // $this->saveLog('info','App\User',$user->id,'users',$logmsg,Auth::user()->id);
                }
          }
           return redirect()->route('job_list.view',['department_id'=>$request->department_id])->with(['success'=>'Changes saved Successfully']); // } catch (\Exception $e) {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        //
    }
    public function departments()
    {
        $company_id=companyId();
            if ($company_id==0) {
               $departments=Department::paginate(10);
            } else {
                $departments=Department::where('company_id',$company_id)->paginate(10);
            }

            return view('jobs.department_list',compact('departments'));
    }
    public function list($department_id)
    {
        $department=Department::find($department_id);
        if ($department) {
        $department=Department::find($department_id);
        $jobs=Job::where('department_id',$department_id)->paginate(10);
        return view('jobs.list',compact('department','jobs'));
         }
        
        return redirect()->back();
    }
    public function skill_search(Request $request)
    {
     
                
        if($request->q==""){
            return "";
        }
       else{
        $name=\App\Skill::where('name','LIKE','%'.$request->q.'%')
                        ->select('id as id','name as text')
                        ->get();
            }
        
        
        return $name;
    
     
    }
    public function job_search(Request $request)
    {
     
                
        if($request->q==""){
            return "";
        }
       else{
        $name=\App\Job::where('title','LIKE','%'.$request->q.'%')
                        ->select('id as id','title as text')
                        ->get();
            }
        
        
        return $name;
    
     
    }
    public function qualification_search(Request $request)
    {
     
                
        if($request->q==""){
            return "";
        }
       else{
        $name=\App\Qualification::where('name','LIKE','%'.$request->q.'%')
                        ->select('id as id','name as text')
                        ->get();
            }
        
        
        return $name;
    
     
    }

    public function delete($job_id)
    {
        $job=Job::find($job_id);
        // return $department->users;
        if ($job->users->count()>0||$job->children->count()>0||$job->listings->count()>0) {
            return 'Job has users and cannot be deleted';
        }
        $job->delete();
        return  response()->json(['success'],200);
    }
}
