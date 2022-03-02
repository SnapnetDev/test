<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\E360PeerEvaluationNominee;

class Approve360ReviewNomination extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $nomination;
    public function __construct(E360PeerEvaluationNominee $nomination)
    {
        //

        $this->nomination=$nomination;
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
            ->subject('Approve 360 Review Peer Nomination')
            ->line('You have been nominated as a peer by '.$this->nomination->user->name.' as their during the forth coming 360 degree review.')
            ->line('Kindly review the nomination and accept or reject.')
             ->action('View Nomination', url('/e360/approve_nominations?employee='.$this->nomination->user->id.'&mp='.$this->nomination->measurement_period->id))

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
            'subject'=>'Approve 360 Review Peer Nomination' ,
            'details'=>"",
            'message'=>'You have been nominated as a peer by '.$this->nomination->user->name.' as their during the forth coming 360 degree review.',
            'action'=>url('/e360/approve_nominations?employee='.$this->nomination->user->id.'&mp='.$this->nomination->measurement_period->id),
            'type'=>'360 Degree Evaluation Nomination',
            'icon'=>'md-file-text'
        ]);

    }
}
