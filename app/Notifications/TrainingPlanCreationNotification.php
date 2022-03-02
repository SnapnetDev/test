<?php

namespace App\Notifications;

use App\Traits\NotificationTrait\SlimNotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TrainingPlanCreationNotification extends Notification
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
		return 'You have a new training plan waiting for approval';
	}

	function getAction()
	{
		return route('app.get',['fetch-training-plan-for-approval']);
	}

	function getSubject()
	{
		return 'Training Plan For Approval';
	}

	function getType()
	{
		return 'Training Plan For Approval';
	}

}
