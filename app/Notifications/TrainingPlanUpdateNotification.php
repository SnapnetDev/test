<?php

namespace App\Notifications;

use App\Traits\NotificationTrait\SlimNotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TrainingPlanUpdateNotification extends Notification
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
		return 'You have an updated training plan waiting for approval';
	}

	function getAction()
	{
		return route('app.get',['fetch-training-plan-for-approval']);
	}

	function getSubject()
	{
		return 'Updated Training Plan For Approval';
	}

	function getType()
	{
		return 'Updated Training Plan For Approval';
	}


}
