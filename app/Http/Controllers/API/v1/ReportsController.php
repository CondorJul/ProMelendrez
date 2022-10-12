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
use Illuminate\Support\Facades\DB;
use Nette\Utils\Arrays;

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

            $path1 = base_path('resources/views/icon.png');
            $type1 = pathinfo($path1, PATHINFO_EXTENSION);
            $data2 = file_get_contents($path1);
            $pic1 = 'data:image/' . $type1 . ';base64,' . base64_encode($data2);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('A4', 'portrait')->loadView('reports2.reports-alls-periods', compact('pic', 'pic1'), $data);

            return $pdf->stream();
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente más tarde';
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
            return 'Surgio un error, intente más tarde';
        }
    }

    public function getAllBussinesAndVisitorsByDate(Request $request)
    {

        $array = DB::select(
            'select
                date("apptmDateTimePrint")
                "apptmDatePrint",
                sum(CASE WHEN "apptKindClient"=1 THEN 1 ELSE 0 END) as business,
                sum(CASE WHEN "apptKindClient"=2 THEN 1 ELSE 0 END) as visitors

                from appointment GROUP BY date("apptmDateTimePrint") ORDER BY date("apptmDateTimePrint") desc;'
        );


        $seriesBusiness = array();
        $seriesBusiness['name'] = "Clientes";
        $seriesBusiness['series'] = array_map(function ($element) {
            return ['name' => $element->apptmDatePrint, 'value' => $element->business];
        }, $array);

        $seriesVisitors = array();
        $seriesBusiness['name'] = "Visitantes";
        $seriesVisitors['series'] = array_map(function ($element) {
            return ['name' => $element->apptmDatePrint, 'value' => $element->visitors];
        }, $array);

        $arrayLineChart = array($seriesBusiness, $seriesVisitors);




        return response()->json([
            'res' => true,
            'msg' => 'Listado correctamente',
            'data' => $arrayLineChart
        ], 200);
    }
}
