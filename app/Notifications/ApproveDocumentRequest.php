<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\DocumentRequest;

class ApproveDocumentRequest extends Notification
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
            ->subject('Approve Document Request')
            ->line('You are to review and approve a request for a document '.$this->document_request->title.' requested for by '.$this->document_request->user->name)
            ->line('Kindly review the application as it is due by '.date('F j, Y',strtotime($this->document_request->due_date)))
            // ->action('View Document Request', url('/document_requests/approvals'))
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
            'subject'=>'Approve Document Request' ,
            'details'=>"<ul class=\"list-group list-group-bordered\">
<li class=\"list-group-item \"><strong>Requested By:</strong><span style\"text-align:right\">".$this->document_request->user->name."</span></li>
                  <li class=\"list-group-item \"><strong>Document Title:</strong><span style\"text-align:right\">".$this->document_request->title."</span></li>
                  <li class=\"list-group-item  \"><strong>Document Type:</strong><span style\"text-align:right\">".$this->document_request->document_request_type->name."</span></li>
                  <li class=\"list-group-item  \"><strong>Document Due Date:</strong><span style\"text-align:right\">".date('F j, Y',strtotime($this->document_request->due_date))."</span></li>
                   </ul>",
            'message'=>'You are to review and approve a request for a document '.$this->document_request->title.' applied for by '.$this->document_request->user->name,
            'action'=>url('document_requests/approvals'),
            'type'=>'Document Request',
            'icon'=>'md-file-text'
        ]);

    }
}
