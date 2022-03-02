<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Payroll;

class ApprovePayroll extends Notification //implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
      public $payroll;
    public function __construct(Payroll $payroll)
    {
        //
        
        $this->payroll=$payroll;
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
                    ->subject('Approve Payroll')
                    ->line('You are to review and approve  Payroll for, '.date('M',strtotime($this->payroll->for)).' '.date('Y',strtotime($this->payroll->for)))
                    ->action('View Payroll', url('compensation/payroll_list')."?month=".date('m-Y',strtotime($this->payroll->for)))
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

            $date=date('Y-m-d',strtotime($this->payroll->for));
       $allowances=0;
       $deductions=0;
       $income_tax=0;
       $salary=0;
       
       $scs=\App\SalaryComponent::where(['status'=>1,'company_id'=>$this->payroll->company_id])->get();
       $components=[];
        $components['ssc_allowances']=0;
          $components['ssc_deductions']=0;
          foreach ($this->payroll->salary_components as $component) {
               $components[$component->constant]=0;
          }
       $extras="";
       foreach ($this->payroll->payroll_details as $detail) {
            $salary+=$detail->basic_pay;
          $allowances+=$detail->allowances;
          $deductions+=$detail->deductions;
          $income_tax+=$detail->paye;
          $pdetails=unserialize($detail->sc_details);
          
          foreach($pdetails['sc_allowances'] as $key=>$allowance){
            $components[$key]+=$allowance;

          }
          foreach($pdetails['sc_deductions'] as $key=>$deduction){
            $components[$key]+=$deduction;

          }

          $components['ssc_allowances']+=$detail->ssc_allowances;
          $components['ssc_deductions']+=$detail->ssc_deductions;
       }
       foreach ($this->payroll->salary_components as  $sc) {
           if( $components[$sc->constant]>0){
             $extras.=" <tr><td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">".$sc->name.":</td>
                                <td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">&#8358;".$components[$sc->constant]."</td></tr>";
           }
       }

      
        return new DatabaseMessage([
            'subject'=>'Approve Payroll',
            'message'=>'You are to review and approve the Payroll for '.$this->payroll->company->name.' in '.date('M',strtotime($this->payroll->for)).' '.date('Y',strtotime($this->payroll->for)).". <br> See below, the Payroll Summary.",
            'details'=>"<table class\"table table-striped\">
                                <tr>
                                <td width=\"40%\" style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">Period</td>
                                <td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">".date('M',strtotime($this->payroll->for)).'-'.date('Y',strtotime($this->payroll->for))."</td>
                                </tr>
                                <tr>
                                <td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">Basic Salary:</td>
                                <td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">&#8358;".$salary."</td>
                                </tr>
                                <tr>
                                <td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">Allowances:</td>
                                <td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">&#8358;".$allowances."</td>
                                </tr>
                                <tr>
                                <td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">Deductions:</td>
                                <td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">&#8358;".$deductions."</td>
                                </tr  >
                                <tr>
                                <td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">PAYE:</td>
                                <td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">&#8358;".$income_tax."</td>
                                </tr>
                               
                                ".$extras."
                                <tr  >
                                <td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">Employee Specific allowances:</td>
                                <td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">&#8358;".$components['ssc_allowances']."</td>
                                </tr>
                                <tr>
                                <td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">Employee Specific Deductions:</td>
                                <td  style=\"border: 1px solid #a4a4a4; padding-left: 5px;\">&#8358;".$components['ssc_deductions']."</td>
                                </tr>
                        </table>",
            'action'=>url('compensation/payroll_list')."?month=".date('m-Y',strtotime($this->payroll->for)),
            'type'=>'Payroll',
            'icon'=>'md-money-box'
        ]);

    }
}
