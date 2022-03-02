<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class KPIsCreated extends Notification 
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $evaluation , $notiMessage,$urlToGo;
    public function __construct($evaluation,$urlToGo)
    {
        //
        $this->evaluation=$evaluation;
        $this->urlToGo=$urlToGo;
        $this->notiMessage="Your Kpi's for the {$evaluation->measurement_period->from} to {$evaluation->measurement_period->to} evaluation period has been set by your line manager, You are to accept or Reject the KPI's assigned to you";

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
                    ->subject('KPIs Created')
                    ->line($this->notiMessage)

                    ->action('Notification Action', url($this->urlToGo))
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
            'subject'=>' KPIs Created',
            'message' => $this->notiMessage,
            'action' => url($this->urlToGo),
            'type'=>'Performance Evaluation'
            ]);
        }
}
