<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Separation;

class SeparationNotifyStaff extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
      public $separation;
    public function __construct(Separation $separation)
    {
        //

        $this->separation=$separation;
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
        ->subject('Employee Separation ')
        ->line('This is to inform you of the Exit of, '.$this->separation->user->name.' effective '.date('Y-m-d',strtotime($this->separation->date_of_separation)))


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
              'subject'=>'Employee Separation ',
            'message'=>'This is to inform you of the Exit of, '.$this->separation->user->name.' effective '.date('Y-m-d',strtotime($this->separation->date_of_separation)),
            'action'=>'#',
            'type'=>'Separation',
            'icon'=>'md-file-text'
        ]);

    }
}
