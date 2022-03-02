<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Confirmation;

class ApproveConfirmationRequest extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $confirmation;
    public function __construct(Confirmation $confirmation)
    {
        //

        $this->confirmation=$confirmation;
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
            ->subject('Approve Confirmation')
            ->line('You are to review and approve a request for a confirmation for '.$this->confirmation->user->name.' initiated by '.$this->confirmation->initiator->name)
            ->line('Kindly review the application .')
             ->action('View Confirmation Approval', url('/confirmation/approvals'))
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
            'subject'=>'Approve Confirmation' ,
            'details'=>"<ul class=\"list-group list-group-bordered\">
<li class=\"list-group-item \"><strong>Confirmation For:</strong><span style\"text-align:right\">".$this->confirmation->user->name."</span></li>
                  <li class=\"list-group-item \"><strong>Confirmation Initiated By:</strong><span style\"text-align:right\">".$this->confirmation->initiator->name."</span></li>
                  <li class=\"list-group-item  \"><strong>Hire Date:</strong><span style\"text-align:right\">".date('F j, Y',strtotime($this->confirmation->hiredate))."</span></li>
                   </ul>",
            'message'=>'You are to review and approve a request for the confirmation '.$this->confirmation->user->name.' initiated by '.$this->confirmation->initiator->name,
            'action'=>url('confirmation/approvals'),
            'type'=>'Confirmation',
            'icon'=>'md-case-check'
        ]);

    }
}
