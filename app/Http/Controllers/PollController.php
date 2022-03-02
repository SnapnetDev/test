<?php

namespace App\Http\Controllers;

use App\Department;
use App\Notifications\NewPollCreatedNotify;
use App\Poll;
use App\PollQuestion;
use App\PollResponse;
use App\Role;
use App\User;
use App\UserGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\RegularExpressionTest;

class PollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function polls(Request $request){
        $user=User::find(Auth::id());
        $name='Polls';
        $polls=Poll::orderBy('id','desc')->where('status','!=','pending');
        if ($request->filled('search')){
            $polls->where('name','like','%'.$request->search.'%')->orwhere('description','like','%'.$request->search.'%');
        }
        $polls->whereRaw("concat(',',replace(replace(roles,'[',''),']',''),',') like '%,$user->role_id,%'");
        $polls->orwhereRaw("concat(',',replace(replace(departments,'[',''),']',''),',') like '%,$user->department_id,%'");
        $polls=$polls->paginate(12);
        return view('polls.polls',compact('polls','name'));
    }

    public function my_polls(){
        $polls=Poll::orderBy('id','desc')->where('user_id',Auth::id())->paginate(12);
        $name='My Polls';
        return view('polls.polls',compact('polls','name'));
    }

    public function createPoll(){
        $company_id=companyId();
        $roles=Role::all();
        $departments=Department::where('company_id',$company_id)->get();
        $groups=UserGroup::where('company_id',$company_id)->get();
        return view('polls.new_poll',compact('departments','roles','groups'));
    }
    public function editPoll($poll){
        $poll=Poll::where('id',$poll)->where('user_id',Auth::id())->first();
        $company_id=companyId();
        $roles=Role::all();
        $departments=Department::where('company_id',$company_id)->get();
        $groups=UserGroup::where('company_id',$company_id)->get();
        return view('polls.edit_poll',compact('departments','roles','groups','poll'));
    }

    public function storePoll(Request $request)
    {
        //return $request->all();
        Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ])->validate();

        $type='normal';
        if ($request->has('anonymous')){
            $type='anonymous';
        }
        if (!$request->has('departments')){
            $request->departments=[];
        }if (!$request->has('groups')){
            $request->groups=[];
        }if (!$request->has('roles')){
            $request->roles=[];
        }
        if ($request->has('all_staff')){
            $request->roles=Role::pluck('id')->toArray();
        }
        $poll = Poll::create(['user_id'=>Auth::id(),'name'=>$request->name,
            'description'=>$request->description,'end_date'=>Carbon::parse($request->end_date)->format('Y-m-d'),
            'status'=>$request->status,'type'=>$type,
            'roles'=>collect(array_map(function($v){return (int) $v;},$request->roles)),'groups'=>collect(array_map(function($v){return (int) $v;},$request->groups)),'departments'=>collect(array_map(function($v){return (int) $v;},$request->departments))]);

       foreach ($request->questions as $question){
            $options=[];
            foreach ($question['option'] as $key=>$option){
                $key=$key+1;
                $option=['id'=>$key,'option'=>$option];
                $options[]=$option;
            }
            $que = new PollQuestion();
            $que->poll_id = $poll->id;
            $que->question = $question['question'];
            $que->options = $options;
            $que->save();
       }
       Session::flash('success','Successfully Created Poll');
        return Redirect::route('view.my.polls');

    }
    public function updatePoll($poll_id,Request $request){

        //return $request->all();
        Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ])->validate();

        $type='normal';
        if ($request->has('anonymous')){
            $type='anonymous';
        }
        if (!$request->has('departments')){
            $request->departments=[];
        }if (!$request->has('groups')){
            $request->groups=[];
        }if (!$request->has('roles')){
            $request->roles=[];
        }
        $poll=Poll::find($poll_id);
        Poll::where('id',$poll_id)->update(['user_id'=>Auth::id(),'name'=>$request->name,
            'description'=>$request->description,'end_date'=>Carbon::parse($request->end_date)->format('Y-m-d'),
            'status'=>$request->status,'type'=>$type,
            'roles'=>collect(array_map(function($v){return (int) $v;},$request->roles)),'groups'=>collect(array_map(function($v){return (int) $v;},$request->groups)),'departments'=>collect(array_map(function($v){return (int) $v;},$request->departments))]);

        PollQuestion::where('poll_id',$poll_id)->delete();
        foreach ($request->questions as $question){
            $options=[];
            foreach ($question['option'] as $key=>$option){
                $key=$key+1;
                $option=['id'=>$key,'option'=>$option];
                $options[]=$option;
            }
            $que = new PollQuestion();
            $que->poll_id = $poll->id;
            $que->question = $question['question'];
            $que->options = $options;
            $que->save();
        }
        Session::flash('success','Successfully Updated Poll');
        return redirect()->route('view.my.polls');

    }
    public function respond($poll_id){
        $user_id=Auth::id();
        $response= PollResponse::where('poll_id',$poll_id)->where('user_id',$user_id)->first();
        $poll=Poll::findorfail($poll_id);
        if ($this->checkUserPoll($poll_id,$user_id)!='granted'){
            Session::flash('fail','You are not eligible to vote');
            return Redirect::route('view.my.polls');
        }
        if ($poll->status!='active'){
            Session::flash('fail','Poll is not Active');
            return Redirect::route('view.polls');
        }
        return view('polls.respond',compact('poll','response'));
    }

    public function submitResponse(Request $request){
        //return $request->all();
        if ($this->checkUserPoll($request->poll_id,Auth::id())!='granted'){
           Session::flash('fail','You are not eligible to vote');
           return Redirect::route('view.polls');
        }
        $poll=Poll::findorfail($request->poll_id);
        if ($poll->status!='active'){
            Session::flash('fail','Poll is not Active');
            return Redirect::route('view.polls');
        }
        $responses=[];
        foreach ($poll->questions as $question){
            $selected=$request[$question->id];
            $response=['question_id'=>$question->id,'selected_option'=>$selected,'other'=>''];
            $responses[]=$response;
        }
       // return $responses;
        PollResponse::updateOrCreate(['user_id'=>Auth::id(),'poll_id'=>$poll->id],['responses'=>$responses]);
        Session::flash('success','Successfully responded in this Poll');
        return Redirect::route('view.polls');
    }
    public function pollResponses($poll_id){
        $poll=Poll::findorfail($poll_id);
        /*if ($poll->user_id!=Auth::id()){
               return 'you cannot see response for this poll';
        }*/
        $responses= PollResponse::where('poll_id',$poll_id)->get();
        if (count($responses)==0){
            return \redirect(route('view.polls'));
        }
        $answers=$this->calculatePoll($poll->id);
        return view('polls.responses',compact('responses','poll','answers'));

    }

    public function calculatePoll($poll){
        $poll=Poll::find($poll);
        $responses= PollResponse::where('poll_id',$poll->id)->get();
        $answers=[];
        foreach ($poll->questions as $question){
            foreach ($question->options as $option){
                $count=0;
                $users=[];
                foreach ($responses as $response) {
                    $answer=collect($response->responses)->where('question_id', $question['id'])->where('selected_option',$option['id'])->count();
                    if ($answer>0){
                        $count++;
                        $users[]=$response->user_id;
                    }
                    $answers[$question['id']][$option['id']]=['count'=>$count,'users'=>$users];
                }
            }
        }
        $poll->results=$answers;
        $poll->save();
       return $answers;
    }

    public function changePollStatus($poll_id,$status){
        $poll=Poll::find($poll_id);
        $poll->status=$status;
        $poll->save();

        $message='Successfully disabled Poll. Staff can not Respond Again';
        if ($status=='active'){
           $message='Successfully enabled Poll. Staff can now Respond';
           $this->notifyUsers($poll_id);
        }

        return $message;
        Session::flash('success',$message);
        return Redirect::route('view.my.polls');
    }

    public function votedUsers(Request $request){
        $users=User::whereIn('id',$request->users)->get();
        foreach ($users as $user){
            $user['poll_response_time']=PollResponse::where('user_id',$user->id)->where('poll_id',$request->poll)->first()->created_at;
        }
        return view('polls.users_voted',compact('users'));
    }

    private function checkUserPoll($poll_id,$user_id){
        $poll=Poll::find($poll_id);
        $user=User::find($user_id);
        $group_users=DB::table('user_group_user')->whereIn('user_group_id',$poll->groups)->pluck('user_id')->toArray();
        if (!in_array($user->role_id, $poll->roles) && !in_array($user->department_id, $poll->departments)&&
            !in_array($user->id, $group_users)) {
            return 'denied';
        }
        return 'granted';
    }

    private function notifyUsers($poll_id){
        $poll=Poll::find($poll_id);
        $company_id=companyId();
        $group_users=DB::table('user_group_user')->whereIn('user_group_id',$poll->groups)->pluck('user_id')->toArray();
        $role_users=User::whereIn('role_id',$poll->roles)->where('company_id',$company_id)->pluck('id')->toArray();
        $department_users=User::whereIn('department_id',$poll->departments)->where('company_id',$company_id)->pluck('id')->toArray();

        $merged = collect($group_users)->merge(collect($role_users));
        $merged2=collect($merged->all());
        $merged2->merge(collect($department_users));
        $list=$merged2->all();

        foreach ($list as $user_id){
            $user=User::find($user_id);
            $user->notify(new NewPollCreatedNotify($poll_id));
        }
    }
}
