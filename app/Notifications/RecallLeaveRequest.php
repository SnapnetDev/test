<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\LeaveRequest;
use App\LeaveRequestRecall;

class RecallLeaveRequest extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
      public $lrc;
    public function __construct(LeaveRequestRecall $lrc)
    {
        //

        $this->lrc=$lrc;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->lrc->leave_request->leave_id==0) {
            $leave_name="Annual Leave";
        }else{
            $leave_name=$this->lrc->leave_request->leave->name;
        }
       return (new MailMessage)
                    ->subject('Recall From Leave ')
                    ->line(' You have been recalled from your '.$leave_name.'  by '.$this->lrc->recaller->name)
           ->line('You are to resume after !'.date('F j, Y',strtotime($this->lrc->new_date)))
                    ->line('Thank you for using our application!');
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
    public function toDatabase($notifiable)
    {
         if ($this->leave_request->leave_id==0) {
            $leave_name="Annual_leave";
        }else{
            $leave_name=$this->leave_request->leave->name;
        }
        return new DatabaseMessage([
            'subject'=>'Approve Leave Request-' .$leave_name,
            'details'=>"<ul class=\"list-group list-group-bordered\">
                  <li class=\"list-group-item \"><strong>Employee Name:</strong><span style\"text-align:right\">".$this->lrc->leave_request->user->name."</span></li>
                  <li class=\"list-group-item  \"><strong>Leave Type:</strong><span style\"text-align:right\">".$leave_name."</span></li>
                  <li class=\"list-group-item \"><strong>Start Date:</strong><span style\"text-align:right\">".date("F j, Y", strtotime($this->lrc->leave_request->start_date))."</span></li>
                  <li class=\"list-group-item \"><strong> New Resumption Date:</strong><span style\"text-align:right\">".date("F j, Y", strtotime($this->lrc->leave_request->end_date))."</span></li>
                  
                </ul>",
            'message'=>' You have been recalled from your '.$leave_name.'  by '.$this->lrc->recaller->name,
            'action'=>url('leave/myrequests'),
            'type'=>'Leave Request',
            'icon'=>'md-calendar'
        ]);

    }
}
