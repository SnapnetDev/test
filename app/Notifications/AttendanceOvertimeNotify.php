<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AttendanceOvertimeNotify extends Notification
{
    use Queueable;
    protected $attendance;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($attendance)
    {
        $this->attendance=$attendance;
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
                    ->line('You are to approve '. $this->attendance->user->name. ' attendance overtime')
                    ->action('Action', route('daily.attendance.report',['date'=>$this->attendance->date]))
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
            'subject'=>$this->attendance->user->name.' Attendance Overtime' ,
            'details'=>"A staff overtime needs your approval",
            'message'=>'A staff overtime needs your approval',
            'action'=> route('daily.attendance.report',['date'=>$this->attendance->date]),
            'type'=>'Attendance Overtime Approval',
            'icon'=>'md-file-text'
        ]);

    }
}
