<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\DocumentRequestApproval;
use App\Stage;

class DocumentRequestPassedStage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

   public $document_request_approval;
      public $stage;
      public $nextstage;
    public function __construct(DocumentRequestApproval $document_request_approval,Stage $stage,Stage $nextstage)
    {
        //
        $this->document_request_approval=$document_request_approval;
        $this->stage=$stage;
        $this->nextstage=$nextstage;
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
        ->subject('Document request Passed an Approval Stage')
        ->line('The document request, '.$this->document_request_approval->document_request->title.' which you submitted for approval has been approved at the '.$this->stage->name.' by '.$this->document_request_approval->approver->name)
        ->line('It has been moved to the'.$this->nextstage->name)
        ->action('View Document Request',  url("document_requests/my_document_requests"))
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
            'subject'=>' Document Request Passed an Approval Stage',
            'message'=>'The, '.$this->document_request_approval->document_request->title.' request which you submitted for approval has been approved at the '.$this->stage->name.' by '.$this->document_request_approval->approver->name,
            'action'=>url("document_requests/my_document_requests"),
            'type'=>'Document Request',
            'icon'=>'md-file-text'
        ]);

    }
}
