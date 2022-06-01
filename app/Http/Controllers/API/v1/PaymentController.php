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
        return response()->json([
            'res' => true,
            'msg' => 'Guardado correctamente',
            'data' => Payment::select()->with('paymentDetails')->where('payId',$p->payId )->first()
        
        ], 200);
        /*$data = [
            'titulo' => 'Styde.net',
            'token'=>123456
        ];
    
        return PDF::loadView('accounting.proof-of-payment', $data)
            ->stream('archivo.pdf');
        */

        /*$s=Payment::select()->with('paymentDetails')->where('payToken', $p->payToken)->first();
        
       $data = [
            'titulo' => 'Styde.net',
            'payment' => $s
        ];
        
        
        $path = base_path('resources/views/logo.png');
        //$path = base_path('storage/global/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data1 = file_get_contents($path);
        $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('b6', 'portrait')->loadView('accounting.proof-of-payment', compact('pic'), $data);

        return $pdf->stream();

     */   
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

    public function proofOfPayment($payToken)
    {
        $p=Payment::select()->with('paymentDetails')->where('payToken', $payToken)->first();
        
       $data = [
            'titulo' => 'Styde.net',
            'payment' => $p
        ];
        
        
        $path = base_path('resources/views/logo.png');
        //$path = base_path('storage/global/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data1 = file_get_contents($path);
        $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper(array(0, 0, 220, 500))->loadView('accounting.proof-of-payment', compact('pic'), $data);

        return $pdf->stream();

        //return PDF::loadView('accounting.proof-of-payment', $data)->stream('archivo.pdf');
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
