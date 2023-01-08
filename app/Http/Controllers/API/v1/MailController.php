<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Mail\PaymentMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{

        public function sendMail($mail){
            $path1 = base_path('resources/views/icon.png');
            $type1 = pathinfo($path1, PATHINFO_EXTENSION);
            $data2 = file_get_contents($path1);
            $pic1 = 'data:image/' . $type1 . ';base64,' . base64_encode($data2);
            
            $details=[
                'title'=>'Correo enviado desde melendresauditores',
                'body'=>'Este es un ejemplo para enviar emails desde gmail',
                'image'=>$pic1
            ];
            Mail::to($mail)->send(new PaymentMail($details));
            
            return "correo enviado correctamente";
        }
        

}
