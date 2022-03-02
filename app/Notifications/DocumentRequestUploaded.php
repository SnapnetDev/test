<?php

namespace App\Notifications;

use App\DocumentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\DocumentRequestApproval;
use App\Stage;

class DocumentRequestUploaded extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

      public $document_request;
    public function __construct(DocumentRequest $document_request)
    {
        //

        $this->document_request=$document_request;
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
        ->subject('Document Requested Uploaded')
        ->line('The Document, '.$this->document_request->title.' which you requested for  on the '.date('Y-m-d',strtotime($this->document_request->created_at)).'('.\Carbon\Carbon::parse($this->document_request->created_at)->diffForHumans().') has been prepared and  uploaded')

        ->action('Download Document Requested', url("document_requests/my_document_requests"))
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
              'subject'=>'Document Requested Uploaded',
            'message'=>'The document request, '.$this->document_request->title.' which you submitted for approval on the '.date('Y-m-d',strtotime($this->document_request->created_at)). 'has been prepared and uploaded.',
            'action'=>url("document_requests/my_document_requests"),
            'type'=>'Document Request',
            'icon'=>'md-file-text'
        ]);

    }
}
