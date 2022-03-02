<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Separation;

class ApproveSeparation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $separation;
    public function __construct(Separation $separation)
    {
        //

        $this->separation=$separation;
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
            ->subject('Approve Separation')
            ->line('You are to review and approve the separation of  '.$this->separation->user->name.' from the company')
             ->action('View Separations', url('/separation/approvals'))
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
            'subject'=>'Approve Employee Separation' ,
            'details'=>">",
            'message'=>'You are to review and approve the separation '.$this->separation->user->name,
            'action'=>url('separation/approvals'),
            'type'=>'Separation',
            'icon'=>'md-file-text'
        ]);

    }
}
