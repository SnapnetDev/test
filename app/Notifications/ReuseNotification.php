<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReuseNotification extends Notification
{
    use Queueable;

    private $payload = [];
//    private $actions = [];
    private $subject = '';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $objMessage = new MailMessage;
        foreach ($this->payload as $item){
            if ($item['type'] == 'line'){
                $objMessage->line($item['data']);
            }
            if ($item['type'] == 'action'){
                $objMessage->action($item['text'],$item['urlAction']);
            }
        }

        if ($this->subject){
            $objMessage->subject($this->subject);
        }

        return $objMessage;
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
    }

    public function appendLine($message){
        $this->payload[] = [
            'data'=>$message,
            'type'=>'line'
        ];

        return $this;
    }

    public function setSubject($subject){

        $this->subject = $subject;
    }

    public function appendAction($text,$urlAction){
        $this->payload[] = [
            'data'=>[
                'text'=>$text,
                'urlAction'=>$urlAction
            ],
            'type'=>'action'
        ];
        return $this;
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
}
