<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\EmployeeReimbursement;

class ApproveEmployeeReimbursement extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $employee_reimbursement;
    public function __construct(EmployeeReimbursement $employee_reimbursement)
    {
        //

        $this->employee_reimbursement=$employee_reimbursement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','mail'];
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
            ->subject('Approve Employee Reimbursement')
            ->line('You are to review and approve employee reimbursement '.$this->employee_reimbursement->title.' requested for by '.$this->employee_reimbursement->user->name)
            ->line('The expense was made on '.date('F j, Y',strtotime($this->employee_reimbursement->expense_date)))
            // ->action('View Employee Reimbursement', url('/employee_reimbursements/approvals'))
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
            'subject'=>'Approve Employee Reimbursement' ,
            'details'=>"<ul class=\"list-group list-group-bordered\">
<li class=\"list-group-item \"><strong>Requested By:</strong><span style\"text-align:right\">".$this->employee_reimbursement->user->name."</span></li>
                  <li class=\"list-group-item \"><strong> Title:</strong><span style\"text-align:right\">".$this->employee_reimbursement->title."</span></li>
                  <li class=\"list-group-item  \"><strong> Type:</strong><span style\"text-align:right\">".$this->employee_reimbursement->employee_reimbursement_type->name."</span></li>
                  <li class=\"list-group-item  \"><strong>Expense Date:</strong><span style\"text-align:right\">".date('F j, Y',strtotime($this->employee_reimbursement->expense_date))."</span></li>
                   </ul>",
            'message'=>'You are to review and approve an employee reimbursement '.$this->employee_reimbursement->title.' applied for by '.$this->employee_reimbursement->user->name,
            'action'=>url('employee_reimbursements/approvals'),
            'type'=>'Employee Reimbursement',
            'icon'=>'md-file-text'
        ]);

    }
}
