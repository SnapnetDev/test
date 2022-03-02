<?php

namespace App\Notifications;

use App\ManualAttendance;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ManualAttendanceNotify extends Notification
{
    private $manualAttendance;
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ma)
    {
        $this->manualAttendance=$ma;
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
        $ma=ManualAttendance::find($this->manualAttendance);
        return new DatabaseMessage([
            'subject'=>'Manual Attendance Created' ,
            'details'=>"A manual attendance created needs your approval",
            'message'=>'A manual attendance created needs your approval',
            'action'=>route('manual.attendance',['date'=>$ma->date]),
            'type'=>'Manual Attendance Approval',
            'icon'=>'md-file-text'
        ]);

    }
}
