<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 4/17/2020
 * Time: 1:26 AM
 */

namespace App\Traits\NotificationTrait;


use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;

trait SlimNotificationTrait
{


	abstract function getMessage();

	abstract function getAction();

	abstract function getSubject();

	abstract function getType();

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
			->subject($this->getSubject())
			->line($this->getMessage())
			->action('View', $this->getAction());
//                    ->line('Thank you for using our application!');
	}


	function toDatabase($notifiable){
		return new DatabaseMessage([
			'subject'=>$this->getSubject(),
			'message'=>$this->getMessage(),
			'details'=>$this->getMessage(),
			'action'=>$this->getAction(),
			'type'=>$this->getType(),
			'icon'=>'md-money-box'
		]);
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