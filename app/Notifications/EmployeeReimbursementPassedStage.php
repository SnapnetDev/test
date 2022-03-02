<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\EmployeeReimbursementApproval;
use App\Stage;

class EmployeeReimbursementPassedStage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

   public $employee_reimbursement_approval;
      public $stage;
      public $nextstage;
    public function __construct(EmployeeReimbursementApproval $employee_reimbursement_approval,Stage $stage,Stage $nextstage)
    {
        //
        $this->employee_reimbursement_approval=$employee_reimbursement_approval;
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
        ->subject('Employee Expense Reimbursement request Passed an Approval Stage')
        ->line('The expense request, '.$this->employee_reimbursement_approval->employee_reimbursement->title.' which you submitted for approval has been approved at the '.$this->stage->name.' by '.$this->employee_reimbursement_approval->approver->name)
        ->line('It has been moved to the'.$this->nextstage->name)
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
            'subject'=>' Document Request Passed an Approval Stage',
            'message'=>'The expense request, '.$this->employee_reimbursement_approval->employee_reimbursement->title.' which you submitted for approval has been approved at the '.$this->stage->name.' by '.$this->employee_reimbursement_approval->approver->name,
            'action'=>url("employee_reimbursements/my_employee_reimbursements"),
            'type'=>'Expense Reimbursement Request',
            'icon'=>'md-file-text'
        ]);

    }
}
