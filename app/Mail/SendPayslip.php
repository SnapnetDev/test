<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use PDF;
use App\Company;
use App\PayrollDetail;
use App\PayrollPolicy;

class SendPayslip extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $detail_id=0;
    public $company_id=0;
    public $pp;
    public function __construct($detail_id,$company_id,$pp)
    {

        

        $this->detail_id = $detail_id;
        $this->company_id=$company_id;
        $this->pp=$pp;
        

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
      
       $detail=PayrollDetail::find($this->detail_id);
       $company=Company::find($this->company_id);
       dd($this->pp->show_all_gross);
         if ($this->pp->show_all_gross===1) {
        $pdf = PDF::loadView('compensation.partials.payslip', compact('detail','company')); //load view page
        return $this->view('emails.payslip_email',compact('detail','company'))
                ->attachData($pdf->stream(), $detail->user->name.' payslip.pdf', [
                    'mime' => 'application/pdf',
                ]);
       }else{
            $pdf = PDF::loadView('compensation.partials.payslip2', compact('detail','company')); //load view page
        return $this->view('emails.payslip_email',compact('detail','company'))
                ->attachData($pdf->stream(), $detail->user->name.' payslip.pdf', [
                    'mime' => 'application/pdf',
                ]);
        }

    }
}
