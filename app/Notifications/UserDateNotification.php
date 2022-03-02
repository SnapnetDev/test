<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\HtmlString;

class UserDateNotification extends Notification/* implements ShouldQueue*/
{
    use Queueable;
    public $notiType, $notiMessage,$urlTogo,$notification_name,$user_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notiType,$notiMessage,$urlTogo,$notification_name,$user_name='')
    {
        //
        $this->user_name=$user_name;
        $this->notiType=$notiType;
        $this->notiMessage=$notiMessage;
        $this->urlTogo=$urlTogo;
        $this->notification_name=$notification_name;
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
                     
                  ->greeting('')
                    ->subject($this->notification_name)
                    ->line(new HtmlString($this->notiMessage))
                     ->line('Regards')

                    ->salutation('Human Capital Management');
              
       
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

    public  function toDatabase($notifiable){

        return [
            'message' => $this->notiMessage,
            'action' => url($this->urlTogo),
            'subject'=> $this->notification_name,
            'type'=>$this->notification_name
        ];
    }

}
