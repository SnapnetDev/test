<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class UpdateKpiInfo extends Notification
{
    use Queueable;

    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public $urlTogo,$notiMessage;
    public function __construct($urlTogo)
    {
        //
        $this->urlTogo=$urlTogo;

        $this->notiMessage='This is to inform you that an update has been made to your kpi , Click on the button below to view.';
    }

    /**
    * Get the notification's delivery channels.
    *
    * @param  mixed  $notifiable
    * @return array
    */
    public function via($notifiable)
    {
        return ['mail'];
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
        ->subject('KPIs Updated')
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
            'action' => url($this->urlTogo),
            'subject'=>' KPIs Updated',
            'type'=>'Performance Evaluation'
            ]);
        }
    }
