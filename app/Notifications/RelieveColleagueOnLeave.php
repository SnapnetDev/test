<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\LeaveRequest;

class RelieveColleagueOnLeave extends Notification 
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
      public $leave_request;
    public function __construct(LeaveRequest $leave_request)
    {
        //

        $this->leave_request=$leave_request;
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
        if ($this->leave_request->leave_id==0) {
            $leave_name="Annual_leave";
        }else{
            $leave_name=$this->leave_request->leave->name;
        }
       return (new MailMessage)
                    ->subject('Approve Leave Request')
                    ->line('You have been appointed by '.$this->leave_request->user->name .' to act as relieve while they are away on leave from the '.date("F j, Y", strtotime($this->leave_request->start_date)).' to the  '.date("F j, Y", strtotime($this->leave_request->end_date)))
                    ->line('Kindly notify your line manager if you will be unavailable!')
                    ->action('View Leave Request', url('/leave/relieve_approvals'))
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
            'subject'=>'Relieve Colleague on Leave-' ,
            'details'=>"<ul class=\"list-group list-group-bordered\">
                  <li class=\"list-group-item \"><strong>Employee Name:</strong><span style\"text-align:right\">".$this->leave_request->user->name."</span></li>
                  <li class=\"list-group-item  \"><strong>Leave Type:</strong><span style\"text-align:right\">".$leave_name."</span></li>
                  <li class=\"list-group-item \"><strong>Start Date:</strong><span style\"text-align:right\">".date("F j, Y", strtotime($this->leave_request->start_date))."</span></li>
                  <li class=\"list-group-item \"><strong>End Date:</strong><span style\"text-align:right\">".date("F j, Y", strtotime($this->leave_request->end_date))."</span></li>
                  <li class=\"list-group-item \"><strong>Priority:</strong><span style\"text-align:right\">". (($this->leave_request->priority==0) ? 'normal' : ($this->leave_request->priority==1?'medium':'high') )."</span></li>
                  <li class=\"list-group-item \"><strong>With Pay:</strong><span style\"text-align:right\">".($this->leave_request->paystatus==0?'without pay':'with pay')."</span></li>
                  <li class=\"list-group-item \"><strong>Reason:</strong><span style\"text-align:right\">".$this->leave_request->reason."</span></li>
                </ul>",
            'message'=>'You have been appointed by '.$this->leave_request->user->name .' to act as relieve while they are away on leave from the '.date("F j, Y", strtotime($this->leave_request->start_date)).' to the  '.date("F j, Y", strtotime($this->leave_request->end_date)).'. Kindly notify your line manager if you will be unavailable!',
            'action'=>url('/leave/relieve_approvals'),
            'type'=>'Leave Request',
            'icon'=>'md-calendar'
        ]);

    }
}
