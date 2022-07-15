<html>

<head>
    <style type="text/css">
        table,
        tr,
        th,
        td {
            /*border: 1px solid black;*/
            border-collapse: collapse;
            background-color: white;
        }

        .datos {
            text-align: left;
            border: 2px solid black;
            border-radius: 10px;
            padding: 5px;
            margin-right: 10px;
        }

        .datos2 {
            text-align: left;
            border: 1px solid black;
            border-radius: 10px;
            padding: 10px;
        }

        .a {
            /*border: 2px solid black;*/
            width: 49%;

        }

        .b {
            /*border: 2px solid black;*/
            width: 49%;
            margin-left: 15px;
        }

        .contenedor {
            margin: 0 auto;
        }

        .contenedor .a,
        .contenedor .b {
            display: inline-block;
        }
    </style>
</head>

<body>
    <div style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
        <h1 style="text-align: center; font-size: 40px; font-family: Arial, Helvetica, sans-serif; text-decoration: underline;">{{ $business['bussName'] }}</h1>
        <Table style="width: 100%;" cellspacing="0">
            <tr>
                <th rowspan="2">
                    <div class="datos">
                        <div class="datos2" style="font-size: 15px;">
                            NÚMERO RUC: <span style="font-weight: lighter;">{{ $business['bussRUC'] }}</span><br>
                            DIRECCIÓN: <span style="font-weight: lighter;">{{ $business['bussAddress'] }}</span><br>
                            REPRESENTATE LEGAL: <span style="font-weight: lighter;">{{ $business->person['perName'] }}</span>
                        </div>
                    </div>
                </th>
                <th>
                    <div style="height: 45px; margin-bottom: -7px;border-left: 2px solid black; border-top: 2px solid black; border-right: 2px solid black; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <span style="font-size: 30px;">N° {{ sprintf('%05d', $business['bussFileNumber']) }}</span>
                    </div>
                </th>
            </tr>
            <tr>
                <th style="width: 10%;">
                    <div style="height: 40px; margin-top: -7px; border: 2px solid black; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                        <img style="border-radius: 10px;" src="<?php echo $pic ?>" alt="" width="150px">
                    </div>
                </th>
            </tr>
        </Table>
        <br>
        <Table style="width: 100%;" border="1" cellspacing="0">
            <tr>
                <th colspan="11" style="font-size: 35px; letter-spacing: 10px;">EJERCICIO {{ $period['prdsNameShort'] }}</th>

                <th colspan="3" style="font-size: 14px;">FECHA DE INICIO DE<br> ACTIVIDADES</th>

                <th colspan="2" style="font-size: 20px;">{{ date_format( date_create ($business['bussDateStartedAct']),"d/m/Y") }}</th>

            </tr>
            <tr>
                <th rowspan="2">PERIODO</th>
                <th rowspan="2">DETALLE</th>
                <th rowspan="2" colspan="2">MENSUALIDAD</th>
                <th rowspan="2" colspan="2">PAGADO</th>
                <th rowspan="2" colspan="2">DEUDA</th>
                <th colspan="5">PAGOS</th>
                <th colspan="2">COMPROBANTE</th>
                <th rowspan="2">RESP. DE<br> COBRO</th>
            </tr>
            <tr>
                <th colspan="2">MONTO</th>
                <th>FECHA</th>
                <th>DOC.</th>
                <th>S/N</th>

                <th>DOC.</th>
                <th>S/N</th>
            </tr>
            @php
            $tm = 0;
            $tp = 0;
            $td = 0;
            @endphp
            @foreach($d_business_period['serviceProvided'] as $key => $value)
            @if((count($value['paymentDetails'])>=1) && ($value->services['svId'] == 1 || $value->services['svId'] == 2))
            @php
            $tm += $value['spCost'];
            $tp += $value['spPaid'];
            $td += $value['spDebt'];
            @endphp
            @foreach ($value['paymentDetails'] as $key=> $val)
            <tr>
                @if($key==0)
                <th rowspan="<?php echo count($value['paymentDetails']) ?>" style="font-weight: lighter;">{{ $value->periodPayments['ppayName'] }}</th>
                <th rowspan="<?php echo count($value['paymentDetails']) ?>" style="width: 25%; font-weight: lighter;">{{ $value->services['svName'] }}</th>
                <th rowspan="<?php echo count($value['paymentDetails']) ?>" style="font-weight: lighter; border-right: 1px solid white;">&nbspS/</th>
                <th rowspan="<?php echo count($value['paymentDetails']) ?>" style="width: 7%;font-weight: lighter; text-align: right;">{{ $value['spCost'] }}</th>
                <th rowspan="<?php echo count($value['paymentDetails']) ?>" style="font-weight: lighter; border-right: 1px solid white;">&nbspS/</th>
                <th rowspan="<?php echo count($value['paymentDetails']) ?>" style="width: 7%; font-weight: lighter; text-align: right;">{{ $value['spPaid'] }}</th>
                <th rowspan="<?php echo count($value['paymentDetails']) ?>" style="font-weight: lighter; border-right: 1px solid white;">&nbspS/</th>
                <th rowspan="<?php echo count($value['paymentDetails']) ?>" style="width: 7%; font-weight: lighter; text-align: right;">{{ $value['spDebt'] }}</th>
                @endif
                <th style="font-weight: lighter; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 6%; font-weight: lighter; text-align: right;">{{ $val['pdsAmount'] }}</th>
                <th style="width: 6%; font-weight: lighter;">{{ date_format( date_create ($val->payments['payDatePrint']),"d/m/y") }}</th>
                <th style="width: 4%; font-weight: lighter;">REC.</th>
                <th style="width: 8%; font-weight: lighter;">{{ $val->payments['paySerie']}}-{{sprintf('%05d', $val->payments['payNumber']) }}</th>
                @if(!($val->payments['payTicketSN'] || $val->payments['payInvoiceSN'] || $val->payments['payReceiptHonorarySN']))
                <th style="width: 4%; font-weight: lighter;">-</th>
                <th style="width: 8%; font-weight: lighter;">-</th>
                @endif
                @if($val->payments['payTicketSN'])
                <th style="width: 4%; font-weight: lighter;">B/V.</th>
                <th style="width: 8%; font-weight: lighter;">{{ $val->payments['payTicketSN'] }}</th>
                @endif
                @if($val->payments['payInvoiceSN'])
                <th style="width: 4%; font-weight: lighter;">FAC.</th>
                <th style="width: 8%; font-weight: lighter;">{{ $val->payments['payInvoiceSN'] }}</th>
                @endif
                @if($val->payments['payReceiptHonorarySN'])
                <th style="width: 4%; font-weight: lighter;">R/H.</th>
                <th style="width: 8%; font-weight: lighter;">{{ $val->payments['payReceiptHonorarySN'] }}</th>
                @endif
                <th style="width: 7%; font-weight: lighter;">{{ strtok($val->payments->user->person['perName'], " ") }}</th>
            </tr>
            @endforeach
            @elseif((count($value['paymentDetails'])==0) && ($value->services['svId'] == 1 || $value->services['svId'] == 2))
            @php
            $tm += $value['spCost'];
            $tp += $value['spPaid'];
            $td += $value['spDebt'];
            @endphp
            <tr>
                <th style="font-weight: lighter;">{{ $value->periodPayments['ppayName'] }}</th>
                <th style="width: 25%; font-weight: lighter;">{{ $value->services['svName'] }}</th>
                <th style="font-weight: lighter; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 7%;font-weight: lighter; text-align: right;">{{ $value['spCost'] }}</th>
                <th style="font-weight: lighter; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 7%; font-weight: lighter; text-align: right;">{{ $value['spPaid'] }}</th>
                <th style="font-weight: lighter; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 7%; font-weight: lighter; text-align: right;">{{ $value['spDebt'] }}</th>
                <th style="font-weight: lighter; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 6%; font-weight: lighter;">-</th>
                <th style="width: 6%; font-weight: lighter;">-</th>
                <th style="width: 4%; font-weight: lighter;">-</th>
                <th style="width: 8%; font-weight: lighter;">-</th>
                <th style="width: 4%; font-weight: lighter;">-</th>
                <th style="width: 8%; font-weight: lighter;">-</th>
                <th style="width: 7%; font-weight: lighter;">-</th>
            </tr>
            @endif
            @endforeach
            <tr>
                <th colspan="2">TOTAL</th>

                <th style="border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 7%;text-align: right;">{{ number_format($tm, 2, '.', '')}}</th>
                <th style="border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 7%; text-align: right;">{{ number_format($tp, 2, '.', '')}}</th>
                <th style="border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 7%; text-align: right;">{{ number_format($td, 2, '.', '')}}</th>
                <th style="border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 6%; text-align: right;">{{ number_format($tp, 2, '.', '')}}</th>
                <th style="width: 6%;">-</th>
                <th style="width: 4%;">-</th>
                <th style="width: 8%;">-</th>
                <th style="width: 4%;">-</th>
                <th style="width: 8%;">-</th>
                <th style="width: 7%;">-</th>
            </tr>
        </Table>
        <br>
        <br>
        <br>
        <div class="contenedor">
            <div class="a">
                <table style="width: 100%;" border="1" cellspacing="0">
                    <tr>
                        <th>OSCE / REMYPE / ARCHIVADORES / OTROS</th>
                        <th>MONTO</th>
                        <th>FECHA</th>
                        <th>DOC.</th>
                        <th>N°</th>
                    </tr>
                    @foreach($table1 as $key)
                    <tr>
                        <th style="font-weight: lighter;">{{ $key->services->svName }}</th>
                        @if(count($key->payment_details)==1)
                        @foreach ($key->payment_details as $val)
                        <th style="width: 50px; font-weight: lighter;">S/&nbsp{{ $val->payments->payTotal }}</th>
                        <th style="width: 50px; font-weight: lighter;">{{ date_format( date_create ($val->payments->payDatePrint),"d/m/y") }}</th>
                        @if(!($val->payments->payTicketSN || $val->payments->payInvoiceSN))
                        <th style="width: 50px; font-weight: lighter;">REC.</th>
                        <th style="width: 70px; font-weight: lighter;">{{ $val->payments->paySerie }}-{{ sprintf('%05d', $val->payments->payNumber) }}</th>
                        @endif
                        @if($val->payments->payTicketSN)
                        <th style="width: 50px; font-weight: lighter;">B/V.</th>
                        <th style="width: 50px; font-weight: lighter;">{{ $val->payments->payTicketSN }}</th>
                        @endif
                        @if($val->payments->payInvoiceSN)
                        <th style="width: 50px; font-weight: lighter;">FAC.</th>
                        <th style="width: 50px; font-weight: lighter;">{{ $val->payments->payInvoiceSN }}</th>
                        @endif
                        @endforeach
                        @elseif(count($key->payment_details)==0)
                        <th style="width: 50px; font-weight: lighter;">S/&nbsp{{ $key->spCost }}</th>
                        <th style="width: 50px; font-weight: lighter;"></th>
                        <th style="width: 50px; font-weight: lighter;"></th>
                        <th style="width: 50px; font-weight: lighter;"></th>
                        @endif
                    </tr>
                    @endforeach
                </table>
            </div>
            <div class="b">
                <table style="width: 100%;" border="1" cellspacing="0">
                    <tr>
                        <th>OSCE / REMYPE / ARCHIVADORES / OTROS</th>
                        <th>MONTO</th>
                        <th>FECHA</th>
                        <th>DOC.</th>
                        <th>N°</th>
                    </tr>
                    @foreach($table2 as $key)
                    <tr>
                        <th style="font-weight: lighter;">{{ $key->services->svName }}</th>
                        @if(count($key->payment_details)==1)
                        @foreach ($key->payment_details as $val)
                        <th style="width: 50px; font-weight: lighter;">S/&nbsp{{ $val->payments->payTotal }}</th>
                        <th style="width: 50px; font-weight: lighter;">{{ date_format( date_create ($val->payments->payDatePrint),"d/m/y") }}</th>
                        @if(!($val->payments->payTicketSN || $val->payments->payInvoiceSN))
                        <th style="width: 50px; font-weight: lighter;">REC.</th>
                        <th style="width: 50px; font-weight: lighter;">{{ sprintf('%05d', $val->payments->payNumber) }}</th>
                        @endif
                        @if($val->payments->payTicketSN)
                        <th style="width: 50px; font-weight: lighter;">B/V.</th>
                        <th style="width: 50px; font-weight: lighter;">{{ $val->payments->payTicketSN }}</th>
                        @endif
                        @if($val->payments->payInvoiceSN)
                        <th style="width: 50px; font-weight: lighter;">FAC.</th>
                        <th style="width: 50px; font-weight: lighter;">{{ $val->payments->payInvoiceSN }}</th>
                        @endif
                        @endforeach
                        @elseif(count($key->payment_details)==0)
                        <th style="width: 50px; font-weight: lighter;">S/&nbsp{{ $key->spCost }}</th>
                        <th style="width: 50px; font-weight: lighter;"></th>
                        <th style="width: 50px; font-weight: lighter;"></th>
                        <th style="width: 50px; font-weight: lighter;"></th>
                        @endif
                    </tr>
                    @endforeach
                    @if(count($table1) != count($table2))
                    <tr>
                        <th style="font-weight: lighter;">-</th>
                        <th style="font-weight: lighter;">-</th>
                        <th style="font-weight: lighter;">-</th>
                        <th style="font-weight: lighter;">-</th>
                        <th style="font-weight: lighter;">-</th>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</body>

</html>
