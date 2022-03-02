<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAttachMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $mail_from;
    protected $mail_subject;
    protected $data;
    protected $view_template;
    protected $attach;
    protected $attach_name;

    /**
     * SendAttachMail constructor.
     * @param $from
     * @param $subject
     * @param $data
     * @param $view
     * @param $attach
     */
    public function __construct($from,$subject,$data,$view,$attach,$attach_name)
    {
        $this->mail_from=$from;
        $this->mail_subject=$subject;
        $this->data=$data;
        $this->view_template=$view;
        $this->attach=$attach;
        $this->attach_name=$attach_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->view_template)
            ->from($this->mail_from,'HCMatrix')
            ->subject($this->mail_subject)
            ->with(['data'=>$this->data])
            ->attachData($this->attach->stream(),$this->attach_name.'.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
