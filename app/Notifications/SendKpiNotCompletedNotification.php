<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendKpiNotCompletedNotification extends Notification
{
    use Queueable;

    private $user = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        //
        $this->user = $user;
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
                    ->subject('KPI Not Completed')
                    ->line('Dear , ' . $this->user->name)
                    ->line('This is to inform you that you have not finished filling your evaluation form.')
                    ->line('Please complete the form before COB today.')
                    ->action('Evaluate Yourself', route('app.get',['evaluate-self']) )
                    ->line('Thank you for using HCMATRIX!');
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
}
