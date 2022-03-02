<?php

namespace App\Notifications;


use App\Confirmation;
use App\ConfirmationRequirement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;


class ConfirmationRequirementUploaded extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

      public $confirmation;
    public $requirement;
    public function __construct(Confirmation $confirmation,ConfirmationRequirement $requirement)
    {
        //

        $this->confirmation=$confirmation;
        $this->requirement=$requirement;
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
        ->subject('Confirmation Requirement Uploaded')
        ->line('The Requirement , '.$this->requirement->name.' which is required for your confirmation has been uploaded on HCMatrix.')

        ->action('Confirmation Process', url("confirmation/my_confirmation_request"))
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
              'subject'=>'Confirmation Requirement Uploaded',
            'message'=>'The Requirement, '.$this->requirement->name.'  which is required for your confirmation has been uploaded on HCMatrix.',
            'action'=>url("confirmation/my_document_requests"),
            'type'=>'Confirmation',
            'icon'=>'md-case-check'
        ]);

    }
}
