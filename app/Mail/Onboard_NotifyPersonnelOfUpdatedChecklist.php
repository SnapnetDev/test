<?php

namespace App\Mail;

use App\OnboardingEmployeeChecklist;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Onboard_NotifyPersonnelOfUpdatedChecklist extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $employee = null;
    private $personnel = null;
    private $checklist = null;

    public function __construct(User $employee,User $personnel, OnboardingEmployeeChecklist $checklist)
    {
        //
        $this->employee= $employee;
        $this->personnel = $personnel;
        $this->checklist = $checklist;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Employee Checklist Update (' . $this->employee->name . ')')
            ->with([
                'employee'=>$this->employee,
                'personnel'=>$this->personnel,
                'checklist'=>$this->checklist
            ])
            ->view('onboard_notifications.notify_personnel_of_updated_checklist');
    }
}
