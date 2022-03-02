<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\PayrollApproval;
use App\Stage;

class PayrollApproved extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $stage;
      public $payroll_approval;
    public function __construct(Stage $stage,PayrollApproval $payroll_approval)
    {
        //
        $this->stage=$stage;
        $this->payroll_approval=$payroll_approval;
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
            ->subject('Payroll Approval request Approved')
            ->line('The payroll approval request for , '.date('M, Y',strtotime($this->payroll_approval->payroll->for)).' which you submitted for approval has been approved.')

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
            'subject'=>'Payroll Approval request Approved',
            'message'=>'The payroll approval request for , '.date('M, Y',strtotime($this->payroll_approval->payroll->for)).' which you submitted for approval has been approved',

            'action'=>url('compensation/payroll_list')."?month=".date('m-Y',strtotime($this->payroll_approval->payroll->for)),
            'type'=>'Payroll',
            'icon'=>'md-money-box'
        ]);


    }
}
