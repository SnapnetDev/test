<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;

class NewUserCreatedNotify extends Notification implements ShouldQueue
{
    use Queueable;
    protected $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user=$user;
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
            ->subject('HCMatrix \"GO LIVE\"')
            ->line('This is to inform you that we \"GO LIVE\" today with the new HCMatrix Human Capital Management Solution for NOVA MERCHANT BANK Ltd.')
                    ->line('Your login details are:')
            ->line('HCMatrix is very dynamic and we need you to visit the application as soon as possible to update your information.')
            ->line('A profile has been created for every user provided by HR.')
            ->line('To sign in, here is what you need to do:')
            ->line('1. Kindly click on the link below to securely access the HCMatrix login page.')
            ->action('Access your account', url('/'))
            ->line('2. Sign in using your office 365 account.')
            ->line('Kindly contact your system administrator if you have any issue.')

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

//    public function toDatabase($notifiable){
//        return new DatabaseMessage([
//            'subject'=>'New Poll Created' ,
//            'details'=>"A new Poll has been created",
//            'message'=>'You are to take part in this Poll',
//            'action'=>route('respond.poll',$this->poll_id),
//            'type'=>'New Poll',
//            'icon'=>'md-file-text'
//        ]);
//
//    }
}
