<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;


class NotifyHrSavePerformanceDiscussion extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $urlTogo,$notiMessage;
    public function __construct($urlTogo,$username)
    {
        //
        $this->urlTogo=$urlTogo;

        $this->notiMessage="This is to inform you that a performance discussion has been added for $username , please click on the button below to view discussion.";
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
        ->line($this->notiMessage)
        ->action('Notification Action', url($this->urlTogo))
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
            'message' => $this->notiMessage,
            'action' => url($this->urlTogo)
            ]);
        }
    }

