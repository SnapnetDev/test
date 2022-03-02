<?php

namespace App\Notifications;

use App\KpiSession;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ThreePartiesNotification extends Notification
{
    use Queueable;

	public $user;
	public $cause;
	private $messageTag = '';
//	private $hr = null;
//	public $qconnect;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(User $user,User $cause,$messageTag)
	{
		//
		$this->user = $user;
		$this->cause = $cause;
		$this->messageTag = $messageTag;
//		$this->hr = $hr;
//		$this->qconnect=$qconnect;
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


	private function sendToUser(){ //evaluate-self
		return (new MailMessage)
			->line('Hello '.$this->user->name)
			->line('This is to notify you that '.$this->cause->name.' has scored your performance on the current Quarterly connect for the year '. (new KpiSession)->getCurrentIntervalObject()->year . ' for quarter ' .  (new KpiSession)->getCurrentIntervalObject()->interval)
			->action('Click Here to view evaluation', route('app.get',['evaluate-self']))
//			->line('Failure to comply will attract a penalty!')
			->line('Regards');

	}
	
	function getGenderPronoun($gender){
		if ($gender == 'M'){
			return 'his';
		}else{
			return 'her';
		}
	}

	private function sendToManager(){ //fetch-user-kpi-evaluation

//		dd($this->cause->name);

		return (new MailMessage)
			->subject('Evaluation Notification')
			->line('Hello (Manager) '.$this->user->name)
			->line('This is to notify you that '.$this->cause->name.' has scored ' . $this->getGenderPronoun($this->cause->sex) . '  performance on the current Quarterly connect for the year '. (new KpiSession)->getCurrentIntervalObject()->year . ' for quarter ' .  (new KpiSession)->getCurrentIntervalObject()->interval->name)
			->action('Click Here to view evaluation', route('app.get',['fetch-user-kpi-evaluation']) . '?user_id=' . $this->cause->id)
//			->line('Failure to comply will attract a penalty!')
			->line('Regards');

	}

	private function sendToHr(){ //fetch-user-kpi-evaluation
		return (new MailMessage)
			->subject('Evaluation Notification')
			->line('Hello (Admin) '.$this->user->name)
			->line('This is to notify you that '.$this->cause->name.' has scored ' . $this->getGenderPronoun($this->cause->sex) . ' direct report on the current Quarterly connect for the year '. (new KpiSession)->getCurrentIntervalObject()->year . ' for quarter ' .  (new KpiSession)->getCurrentIntervalObject()->interval->name)
			->action('Click Here to view evaluation', route('app.get',['fetch-user-kpi-evaluation']) . '?user_id=' . $this->cause->id)
//			->line('Failure to comply will attract a penalty!')
			->line('Regards');
	}

	public function toMail($notifiable)
	{

		if ($this->messageTag == 'hr'){
		   return $this->sendToHr();
		}else if ($this->messageTag == 'manager'){
		   return $this->sendToManager();
		}else if ($this->messageTag == 'user'){
		   return $this->sendToUser();
		}

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
