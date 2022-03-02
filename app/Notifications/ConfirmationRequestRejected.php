<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\ConfirmationApproval;
use App\Stage;

class ConfirmationRequestRejected extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $stage;
      public $document_request_approval;
    public function __construct(Stage $stage,ConfirmationApproval $confirmation_approval)
    {
        //
        $this->stage=$stage;
        $this->confirmation_approval=$confirmation_approval;
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

//'You are to review and approve a request for a document '.$this->document_request_approval->document_request->title.' applied for by '.$this->document_request->user->name

         return (new MailMessage)
        ->subject('Confirmation Request Rejected')
             ->line('Your confirmation process, which was initiated on the '.date('Y-m-d',strtotime($this->confirmation_approval->confirmation->created_at)).'('.\Carbon\Carbon::parse($this->confirmation_approval->confirmation->created_at)->diffForHumans().') has been rejected')

        ->action('View Confirmation Request', url("confirmation/my_confirmation_requests"))
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
            'subject'=>'Confirmation Request Rejected',
            'message'=>'Your confirmation process, which was initiated on the '.date('Y-m-d',strtotime($this->confirmation_approval->confirmation->created_at)).'('.\Carbon\Carbon::parse($this->confirmation_approval->confirmation->created_at)->diffForHumans().' has been rejected',
            'action'=>url("confirmation/my_confirmation_requests"),
            'type'=>'Confirmation Request',
            'icon'=>'md-case-check'
        ]);

    }
}
