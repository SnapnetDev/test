<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;


class ApproveDependantChange extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $user;
    public function __construct(User $user)
    {
        //

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
            ->subject('Approve Dependant Change')
            ->line('You are to review and approve changes made by '.$this->user->name.' to their Dependants Information.')
             ->action('View  Request', url('/userprofile/dependant_change_approval'))
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
            'subject'=>'Approve Dependant Change' ,
            'details'=>'',
            'message'=>'You are to review and approve changes made by '.$this->user->name.' to their Dependants Information.',
            'action'=>url('/userprofile/dependant_change_approval'),
            'type'=>'Academic History Change Approval',
            'icon'=>'md-account'
        ]);

    }
}
