<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\LeaveRequest;

class ApproveLoanRequest extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
      public $leave_request;
    public function __construct(LeaveRequest $leave_request)
    {
        //

        $this->leave_request=$leave_request;
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
                    ->subject('Approve Leave Request')
                    ->line('You are to review and approve a leave request, '.$this->leave_request->leave->name.' applied for by '.$this->leave_request->user->name)
                    // ->action('View Leave Request', url('/documents/reviews'))
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
            'subject'=>'Approve Leave Request-' .$this->leave_request->leave->name,
            'message'=>'You are to review and approve a leave request '.$this->leave_request->leave->name.' applied for by '.$this->leave_request->user->name,
            // 'action'=>route('documents.showreview', ['id'=>$this->document->id]),
            'type'=>'Leave Request'
        ]);

    }
}
