<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Job;

class NewJobOpening extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
      public $job;
    public function __construct(Job $job)
    {
        //
        
        $this->job=$job;
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
                    ->subject('New Job Opening')
                    ->line('This is to notify you that a new job, '.$this->job->title.', in the '.$this->job->department->name.' has been created by the HR  ')
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
            'subject'=>'New Job Opening-' .$this->job->title,
            'details'=>"<ul class=\"list-group list-group-bordered\">
                  <li class=\"list-group-item \"><strong>Title:</strong><span style\"text-align:right\">".$this->job->title."</span></li>
                  <li class=\"list-group-item  \"><strong>Department:</strong><span style\"text-align:right\">".$this->job->department->name."</span></li>
                  <li class=\"list-group-item \"><strong>Description:</strong><span style\"text-align:right\">".$this->job->description."</span></li>
                  

                  <li class=\"list-group-item \"><strong>Least Qualification:</strong><span style\"text-align:right\">". $this->job->qualification ? $this->job->qualification->name : ''."</span></li>
                  
                </ul>",
            'message'=>'A new job, '.$this->job->title.', in the '.$this->job->department->name.' has been created by the HR',
            // 'action'=>route('documents.showreview', ['id'=>$this->document->id]),
            'type'=>'New Job Opening',
            'icon'=>'md-assignment',
            'action'=>url('jobs').'/'.$this->job->id

        ]);

    }
}
