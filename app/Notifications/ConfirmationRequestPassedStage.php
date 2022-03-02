<?php

namespace App\Notifications;

use App\Confirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\ConfirmationApproval;
use App\Stage;

class ConfirmationRequestPassedStage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

   public $confirmation_approval;
      public $stage;
      public $nextstage;
    public function __construct(ConfirmationApproval $confirmation_approval,Stage $stage,Stage $nextstage)
    {
        //
        $this->confirmation_approval=$confirmation_approval;
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
        ->subject('Confirmation Passed an Approval Stage')
        ->line('Your confirmation process,  which was submitted for approval has been approved at the '.$this->stage->name.' by '.$this->confirmation_approval->approver->name)
        ->line('It has been moved to the'.$this->nextstage->name)
        ->action('View My Confirmation Process',  url("confirmation/my_confirmation_request"))
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
            'subject'=>' Confirmation Passed an Approval Stage',
            'message'=>'Your confirmation process,  which was submitted for approval has been approved at the '.$this->stage->name.' by '.$this->confirmation_approval->approver->name,
            'action'=>url("confirmation/my_confirmation_request"),
            'type'=>'Confirmation',
            'icon'=>'md-case-check'
        ]);

    }
}
