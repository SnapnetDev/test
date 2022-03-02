<?php

namespace App\Notifications;

use App\Traits\NotificationTrait\SlimNotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TrainingPlanRejectionNotification extends Notification
{
    use Queueable;

    use SlimNotificationTrait;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


	function getMessage()
	{
		return 'Your request has been rejected by HR, please click to view reason';
	}

	function getAction()
	{
		return route('app.get',['fetch-training-plan']);
	}

	function getSubject()
	{
		return 'Training Plan Request Rejected';
	}

	function getType()
	{
		return 'Training Plan Request Rejected';
	}

}
