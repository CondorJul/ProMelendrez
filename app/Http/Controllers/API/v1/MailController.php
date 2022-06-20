<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Mail\PaymentMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{

        public function sendMail(){
            $details=[
                'title'=>'Correo enviado desde melendresauditores',
                'body'=>'Este es un ejemplo para enviar emails desde gmail'
            ];
            Mail::to('ricki.0912@gmail.com')->send(new PaymentMail($details));
            return "correo enviado correctamente";
        }
        

}
