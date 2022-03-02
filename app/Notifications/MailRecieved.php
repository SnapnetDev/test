<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\AARMailMangement;
class MailRecieved extends Notification
{
    use Queueable;

    public $MM;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AARMailMangement $mailManagement)
    {
        $this->MM=$mailManagement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
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
            'subject'=>'New Mail' ,
            'details'=>"You have a new mail",
            'message'=>'You are to acknowledge the receipt of a mail sent to you from '.$this->MM->sender,
            'action'=>url('/mail-acknowledge/'.$this->MM->id),
            'type'=>'Mail Management',
            'icon'=>'md-email'
        ]);

    }
}
