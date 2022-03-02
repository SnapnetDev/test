<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\LeaveApproval;
use App\Stage;

class LeaveRequestPassedStage extends Notification 
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

   public $leave_approval;
      public $stage;
      public $nextstage;
    public function __construct(LeaveApproval $leave_approval,Stage $stage,Stage $nextstage)
    {
        //
        $this->leave_approval=$leave_approval;
        $this->stage=$stage;
        $this->nextstage=$nextstage;
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
         if ($this->leave_approval->leave_request->leave_id==0) {
            $leave_name="Annual_leave";
        }else{
            $leave_name=$this->leave_approval->leave_request->leave->name;
        }
        return (new MailMessage)
        ->subject('Leave request Passed an Approval Stage')
        ->line('The leave request, '.$leave_name.' which you submitted for approval has been approved at the '.$this->stage->name.' by '.$this->leave_approval->approver->name)
        ->line('It has been moved to the'.$this->nextstage->name)
        ->action('View Leave Request',  url("leave/myrequests"))
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
         if ($this->leave_approval->leave_request->leave_id==0) {
            $leave_name="Annual_leave";
        }else{
            $leave_name=$this->leave_approval->leave_request->leave->name;
        }
        return new DatabaseMessage([
            'subject'=>$leave_name.' -Leave Request Passed an Approval Stage',
            'message'=>'The, '.$leave_name.' request which you submitted for approval has been approved at the '.$this->stage->name.' by '.$this->leave_approval->approver->name,
            'action'=>url("leave/myrequests"),
            'type'=>'LeaveRequest',
            'icon'=>'md-calendar'
        ]);

    }
}
