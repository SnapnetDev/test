<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\SeparationApproval;
use App\Stage;

class SeparationPassedStage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

   public $separation_approval;
      public $stage;
      public $nextstage;
    public function __construct(SeparationApproval $separation_approval,Stage $stage,Stage $nextstage)
    {
        //
        $this->separation_approval=$separation_approval;
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
        ->subject('Document request Passed an Approval Stage')
        ->line('The Separation of, '.$this->separation_approval->separation->user->name.' which you submitted for approval has been approved at the '.$this->stage->name.' by '.$this->separation_approval->approver->name)
        ->line('It has been moved to the'.$this->nextstage->name)
        ->action('View Leave Request',  url("separation"))
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
            'subject'=>' Separation Passed an Approval Stage',
            'message'=>'The Separation of, '.$this->separation_approval->separation->user->name.' which you submitted for approval has been approved at the '.$this->stage->name.' by '.$this->separation_approval->approver->name,
            'action'=>url("separation"),
            'type'=>'Separation',
            'icon'=>'md-file-text'
        ]);

    }
}
