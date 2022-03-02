<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class AnnualLeaveSpill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:annualleavespill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command runs at the end of the year and computes the leave left at the end of the year for each staff';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $users=User::where('status','!=',2)->where('company_id',8)->get();
        foreach ($users as $user) {
        $leave['lp']=\App\LeavePolicy::where('company_id',$user->company_id)->first();
        
        if ($leave['lp']->uses_spillover==1) {
            $leave=[];
            $leave['user']=$user;
            $this->getEntitledOldLeave($leave);
            $this->getEntitledLeave($leave);

            $this->saveLeaveSpill($leave);
            // $leaveleft=$leavebank-$used_days;
        }
        
        }
        
    }
    public function getEntitledOldLeave(&$leave)
    {
        
        $leave['old_leave_days']=0;
                     $leave['old_leave_used']=0;
        if (date('Y',strtotime( $leave['user']->hiredate))<date('Y') ) {
            
                $user_leave_spill= $leave['user']->leave_spills()->where(['from_year'=>date('Y',strtotime("-1 year")),'to_year'=>date('Y')])->first();
                if ($user_leave_spill) {
                    $oldleaveleft=$user_leave_spill->days-$user_leave_spill->used;
                     $leave['old_leave_days']=$user_leave_spill->days;
                     $leave['old_leave_used']=$user_leave_spill->used;
                }

            
        }
       
    }
    public function getEntitledLeave(&$leave)
    {
        $leave['leave_days']=0;
                     $leave['leave_used']=0;
                     $leave['lp']=\App\LeavePolicy::where('company_id',$leave['user']->company_id)->first();
        if ( $leave['user']->grade) {
            if ( $leave['user']->grade->leave_length>0) {
                $leave['leavebank']= $leave['user']->grade->leave_length;
                  $leave['leave_days']=$leave['user']->grade->leave_length;
               
            }else{
                $leave['leavebank']=$leave['lp']->default_length;
                 $leave['leave_days']=$leave['lp']->default_length;
               
            }
            
        }else{
                $leave['leavebank']=$leave['lp']->default_length;
                 $leave['leave_days']=$leave['lp']->default_length;
               
        }
       

        
       if (date('Y',strtotime($leave['user']->hiredate))== date('Y')) {
            //porate for staff employed this year
            $leave['leavebank']=$leave['leavebank']/12*(12-intval(date('m',strtotime( $leave['user']->hiredate)))+1);
           $leave['leave_days']=$leave['leavebank']/12*(12-intval(date('m',strtotime( $leave['user']->hiredate)))+1);
             }
             $leave['leave_used']= $leave['user']->leave_requests()->whereYear('start_date', date('Y'))->where(['status'=>1,'leave_id'=>0])->sum('length');
    }

    public function saveLeaveSpill(&$leave)
    {
         $leave['lp']=\App\LeavePolicy::where('company_id',$leave['user']->company_id)->first();
       $leave['annual_leave_left']=$leave['leave_days']+$leave['old_leave_used']- $leave['leave_used'];
       if ($leave['lp']->uses_maximum_spillover==1) {
        if ($leave['annual_leave_left']>=$leave['lp']->spillover_length) {
           \App\LeaveSpill::updateOrCreate(['user_id'=>$leave['user']->id,'from_year'=>date('Y',strtotime("-1 year")),'to_year'=>date('Y')],['user_id'=>$leave['user']->id,'from_year'=>date('Y',strtotime("-1 year")),'to_year'=>date('Y'),'days'=>$leave['lp']->spillover_length,'actual_days'=>$leave['annual_leave_left'],'company_id'=>$leave['user']->company_id]);
        }else{

           \App\LeaveSpill::updateOrCreate(['user_id'=>$leave['user']->id,'from_year'=>date('Y',strtotime("-1 year")),'to_year'=>date('Y')],['user_id'=>$leave['user']->id,'from_year'=>date('Y',strtotime("-1 year")),'to_year'=>date('Y'),'days'=>$leave['annual_leave_left'],'actual_days'=>$leave['annual_leave_left'],'company_id'=>$leave['user']->company_id]);
        }
       }else{
       \App\LeaveSpill::updateOrCreate(['user_id'=>$leave['user']->id,'from_year'=>date('Y',strtotime("-1 year")),'to_year'=>date('Y')],['user_id'=>$leave['user']->id,'from_year'=>date('Y',strtotime("-1 year")),'to_year'=>date('Y'),'days'=>$leave['annual_leave_left'],'actual_days'=>$leave['annual_leave_left'],'company_id'=>$leave['user']->company_id]);
       }
       

    }
}
