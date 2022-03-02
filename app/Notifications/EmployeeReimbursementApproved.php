<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\EmployeeReimbursementApproval;
use App\Stage;

class EmployeeReimbursementApproved extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $stage;
      public $employee_reimbursement_approval;
    public function __construct(Stage $stage,EmployeeReimbursementApproval $employee_reimbursement_approval)
    {
        //
        $this->stage=$stage;
        $this->employee_reimbursement_approval=$employee_reimbursement_approval;
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

//'You are to review and approve a request for a document '.$this->employee_reimbursement_approval->employee_reimbursement->title.' applied for by '.$this->employee_reimbursement->user->name

         return (new MailMessage)
        ->subject('Expense Reimbursement Approved')
        ->line('The Expense Reimbursement, '.$this->employee_reimbursement_approval->employee_reimbursement->title.' which you requested for  on the '.date('Y-m-d',strtotime($this->employee_reimbursement_approval->employee_reimbursement->created_at)).'('.\Carbon\Carbon::parse($this->employee_reimbursement_approval->employee_reimbursement->created_at)->diffForHumans().') has been approved at the final stage')

        ->action('View Expense Reimbursement', url("employee_reimbursements/my_employee_reimbursements"))
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
              'subject'=>'Expense Reimbursement Approved',
            'message'=>'The Expense Reimbursement, '.$this->employee_reimbursement_approval->employee_reimbursement->title.' which you submitted for approval on the '.date('Y-m-d',strtotime($this->stage->workflow->name)). 'has been approved at the final stage.',
            'action'=>url("employee_reimbursements/my_employee_reimbursements"),
            'type'=>'Expense Reimbursement',
            'icon'=>'md-file-text'
        ]);

    }
}
