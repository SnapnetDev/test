<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\SeparationApproval;
use App\Stage;

class SeparationApproved extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $stage;
      public $separation_approval;
    public function __construct(Stage $stage,SeparationApproval $separation_approval)
    {
        //
        $this->stage=$stage;
        $this->separation_approval=$separation_approval;
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

//'You are to review and approve a request for a document '.$this->employee_reimbursement_approval->employee_reimbursement->title.' applied for by '.$this->employee_reimbursement->user->name

         return (new MailMessage)
        ->subject('Separation Approved')
        ->line('The Separation of, '.$this->separation_approval->separation->user->name.' which you started on the '.date('Y-m-d',strtotime($this->separation_approval->separation->created_at)).'('.\Carbon\Carbon::parse($this->separation_approval->separation->created_at)->diffForHumans().') has been approved at the final stage')

        ->action('View Separation', url("separation"))
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
              'subject'=>'Separation Approved',
            'message'=>'The Separation of, '.$this->separation_approval->separation->user->name.' which you started on the '.date('Y-m-d',strtotime($this->separation_approval->separation->created_at)).'('.\Carbon\Carbon::parse($this->separation_approval->separation->created_at)->diffForHumans().') has been approved at the final stage',
            'action'=>url("separation"),
            'type'=>'Separation',
            'icon'=>'md-file-text'
        ]);

    }
}
