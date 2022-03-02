<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Separation;

class CompleteSeparationForm extends Notification
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
            ->subject('Complete Exit interview Form')
            ->line('Dear, '.$this->separation->user->name)
            ->line('You are to complete an exit interview form as part of the process of your disengagement from   '.$this->separation->user->company->name)
             ->action('Complete Form', url('/separation/separation_form?separation='.$this->separation->id))
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
            'subject'=>'Complete Exit interview Form' ,
            'details'=>">",
            'message'=>'You are to complete an exit interview form as part of the process of your disengagement from the company ',
            'action'=>url('/separation/separation_form?separation='.$this->separation->id),
            'type'=>'Separation',
            'icon'=>'md-file-text'
        ]);

    }
}
