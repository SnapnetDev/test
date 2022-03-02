<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\PayrollApproval;
use App\Stage;

class PayrollPassedStage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

   public $payroll_approval;
      public $stage;
      public $nextstage;
    public function __construct(PayrollApproval $payroll_approval,Stage $stage,Stage $nextstage)
    {
        //
        $this->payroll_approval=$payroll_approval;
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

        return (new MailMessage)
        ->subject('Payroll Approval request Passed an Approval Stage')
        ->line('The payroll approval request for , '.date('M, Y',strtotime($this->payroll_approval->payroll->for)).' which you submitted for approval has been approved at the '.$this->stage->name.' by '.$this->payroll_approval->approver->name)
        ->line('It has been moved to the'.$this->nextstage->name)
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

        return new DatabaseMessage([
            'subject'=>'Payroll Approval request Passed an Approval Stage',
            'message'=>'The payroll approval request for , '.date('M, Y',strtotime($this->payroll_approval->payroll->for)).' which you submitted for approval has been approved at the '.$this->stage->name.' by '.$this->payroll_approval->approver->name,
            'action'=>url('compensation/payroll_list')."?month=".date('m-Y',strtotime($this->payroll_approval->payroll->for)),
            'type'=>'Payroll',
            'icon'=>'md-money-box'
        ]);

    }
}
