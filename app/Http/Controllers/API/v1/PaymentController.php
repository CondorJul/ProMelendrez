<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Payment;
use App\Models\PaymentDetail;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $p=Payment::create($request->all());
        $p->paymentDetails()->createMany($request->paymentDetails);
        $p->payState=3;/*Facturado */
        $p->save();
        $data = [
            'titulo' => 'Styde.net',
            'token'=>123456
        ];
    
        return PDF::loadView('accounting.proof-of-payment', $data)
            ->stream('archivo.pdf');

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function proofOfPayment($token)
    {
        $data = [
            'titulo' => 'Styde.net',
            'token'=>$token
        ];
    
        return PDF::loadView('accounting.proof-of-payment', $data)
            ->stream('archivo.pdf');
    
        //return $pdf->download('mi-archivo.pdf');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}