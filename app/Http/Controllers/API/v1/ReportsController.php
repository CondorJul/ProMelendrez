<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\DBusinessPeriod;
use App\Models\Period;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ReportsController extends Controller
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
        //
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function reportControlMonitoring($periodId, $bussId)
    {
        try {
            $dbp = DBusinessPeriod::select()->with('serviceProvided')
                ->with('serviceProvided.services')
                ->with('serviceProvided.periodPayments')
                ->with('serviceProvided.paymentDetails.payments.user.person')
                ->where('prdsId', $periodId)
                ->where('bussId', $bussId)->first();
            $p = Period::where('prdsId', $dbp->prdsId)->first();
            $b = Business::with('person')->where('bussId', $dbp->bussId)->first();

            $data = [
                'd_business_period' => $dbp,
                'period' => $p,
                'business' => $b,
            ];
            $path = base_path('resources/views/logo.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path);
            $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('A4', 'landscape')->loadView('reports2.exercise-monitoring', compact('pic'), $data);

            return $pdf->stream();
        } catch (Exception $e) {
            return 'Surgio un error, intente más tarde';
        }
    }

    public function controlMonitoringJson($periodId, $bussId)
    {
        try {
            $dbp = DBusinessPeriod::select()->with('serviceProvided')
                ->with('serviceProvided.services')
                ->with('serviceProvided.periodPayments')
                ->with('serviceProvided.paymentDetails.payments.user.person')
                ->where('prdsId', $periodId)
                ->where('bussId', $bussId)->first();
            $p = Period::where('prdsId', $dbp->prdsId)->first();
            $b = Business::with('person')->where('bussId', $dbp->bussId)->first();

            $data = [
                'd_business_period' => $dbp,
                'period' => $p,
                'business' => $b,
            ];

            return response()->json($data);
        } catch (Exception $e) {
            return 'Surgio un error, intente más tarde';
        }
    }

    public function reportAllPeriods($bussId)
    {
        try {
            $dbp = DBusinessPeriod::select()
                ->join('periods', 'periods.prdsId', '=', 'd_bussines_periods.prdsId')
                ->with('serviceProvided')
                ->with('periods')
                ->with('serviceProvided.services')
                ->with('serviceProvided.periodPayments')
                ->where('bussId', $bussId)
                ->orderBy('periods.prdsNameShort', 'ASC')->get();
            $b = Business::with('person')->where('bussId', $bussId)->first();
            /*setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
            $f = iconv('ISO-8859-2', 'UTF-8', strftime("%d de %B de %Y", strtotime(date('F j, Y, g:i a'))));
*/
            $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            $fecha = Carbon::parse(date('m/d/y'));
            $mes = $meses[($fecha->format('m')) - 1];
            $f = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

            $data = [
                'd_business_period' => $dbp,
                'business' => $b,
                'date' => $f
            ];
            $path = base_path('resources/views/logo.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path);
            $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('A4', 'portrait')->loadView('reports2.report-all-periods', compact('pic'), $data);

            return $pdf->stream();
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente más tarde';
        }
    }

    public function reportAllPeriodsJson($bussId)
    {
        try {
            $dbp = DBusinessPeriod::select()->with('serviceProvided')
                ->with('periods')
                ->with('serviceProvided.services')
                ->with('serviceProvided.periodPayments')
                ->where('bussId', $bussId)->get();
            $b = Business::with('person')->where('bussId', $bussId)->first();
            setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
            $f = iconv('ISO-8859-2', 'UTF-8', strftime("%d de %B de %Y", strtotime(date('F j, Y, g:i a'))));

            $data = [
                'd_business_period' => $dbp,
                'business' => $b,
                'date' => $f
            ];

            return response()->json($data);
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente más tarde';
        }
    }
}
