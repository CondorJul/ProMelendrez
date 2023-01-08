<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

     public $details;
    public function __construct($details)
    {
        $this->details=$details;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        $pdf=file_get_contents('https://api.melendresauditores.com/v1/payments/ZYWuVuflVbIs652/proof-of-payment');
        return $this->subject('Prueba kkkkkkkde correo auditores melendres')->view('mails.test')->attachData($pdf, "test.pdf");;
        //return $this->view('view.name');
    }
}
