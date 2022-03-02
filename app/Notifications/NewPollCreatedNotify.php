<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewPollCreatedNotify extends Notification
{
    use Queueable;
    protected $poll_id;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($poll)
    {
        $this->poll_id=$poll;
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

    public function toDatabase($notifiable){
        return new DatabaseMessage([
            'subject'=>'New Poll Created' ,
            'details'=>"A new Poll has been created",
            'message'=>'You are to take part in this Poll',
            'action'=>route('respond.poll',$this->poll_id),
            'type'=>'New Poll',
            'icon'=>'md-file-text'
        ]);

    }
}
