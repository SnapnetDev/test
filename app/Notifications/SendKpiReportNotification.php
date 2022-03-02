<?php

namespace App\Notifications;

use App\KpiSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class SendKpiReportNotification extends Notification
{
    use Queueable;

    private $report = '';
    private $ccs = [];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($report_,$ccs_=[])
    {
        //
        $this->report = $report_;
        $this->ccs = $ccs_;
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
        return (new MailMessage)
                    ->cc($this->ccs)
                    ->subject('Evaluation Report ' . (new KpiSession)->getCurrentInterval())
                    ->line(new HtmlString($this->report))
                    ->line('Thank you for using HC-Matrix!');

//        ->subject(request()->get('title'))
//        ->line(request()->get('title'))
//        ->line(new HtmlString(request()->get('message')))
//        ->action('Evaluate Yourself', route('app.get',['evaluate-self']))
//        ->line('Thank you for using HC-Matrix!');

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
