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

            $filter = array_filter(json_decode($dbp->serviceProvided), function ($item) {
                return (!($item->svId == 1 || $item->svId == 2));
            });

            $numFilas = count($filter);

            /*$otherServ = [
                'table1' => array_slice($filter, 0, ceil($numFilas / 2)),
                'table2' => array_slice($filter, ceil($numFilas / 2), $numFilas - 1)
            ];*/

            $data = [
                'd_business_period' => $dbp,
                'period' => $p,
                'business' => $b,
                'table1' => array_slice($filter, 0, ceil($numFilas / 2)),
                'table2' => array_slice($filter, ceil($numFilas / 2), $numFilas - 1)
            ];

            $path = base_path('resources/views/v1.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path);
            $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('A4', 'landscape')->loadView('reports2.exercise-monitoring', compact('pic'), $data);

            return $pdf->stream();
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente m??s tarde';
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

            $filter = array_filter(json_decode($dbp->serviceProvided), function ($item) {
                return (!($item->svId == 1 || $item->svId == 2));
            });

            $numFilas = count($filter);

            /*$otherServ = [
                'table1' => array_slice($filter, 0, ceil($numFilas / 2)),
                'table2' => array_slice($filter, ceil($numFilas / 2), $numFilas - 1)
            ];*/

            $data = [
                'd_business_period' => $dbp,
                'period' => $p,
                'business' => $b,
                'table1' => array_slice($filter, 0, ceil($numFilas / 2)),
                'table2' => array_slice($filter, ceil($numFilas / 2), $numFilas - 1)
            ];

            return response()->json($data);
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente m??s tarde';
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
            $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            $fecha = Carbon::parse(date('m/d/y'));
            $mes = $meses[($fecha->format('m')) - 1];
            $f = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

            $data = [
                'd_business_period' => $dbp,
                'business' => $b,
                'date' => $f
            ];
            $path = base_path('resources/views/v1.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path);
            $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('A4', 'portrait')->loadView('reports2.report-all-periods', compact('pic'), $data);

            return $pdf->stream();
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente m??s tarde';
        }
    }

    public function reportAllPeriodsJson($bussId)
    {
        try {
            $dbp = DBusinessPeriod::select()
                ->join('periods', 'periods.prdsId', '=', 'd_bussines_periods.prdsId')
                ->with('serviceProvided')
                ->with('periods')
                ->with('serviceProvided.services')
                ->with('serviceProvided.periodPayments')
                ->where('bussId', $bussId)->get();
            $b = Business::with('person')->where('bussId', $bussId)->first();
            setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
            $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            $fecha = Carbon::parse(date('m/d/y'));
            $mes = $meses[($fecha->format('m')) - 1];
            $f = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

            $data = [
                'd_business_period' => $dbp,
                'business' => $b,
                'date' => $f
            ];

            return response()->json($data);
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente m??s tarde';
        }
    }
}
