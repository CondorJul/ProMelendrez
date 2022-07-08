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
                <th style="width: 150px; border: 1px solid black;"><span style="font-size: 30px;">N° 000004</span></th>
            </tr>
            <tr>
                <th style="border: 1px solid black;"><img src="<?php echo $pic ?>" alt="" width="150px"></th>
            </tr>
        </Table>
        <br>
        <Table style="width: 100%;" border="1" cellspacing="0">
            <tr>
                <th colspan="9" style="font-size: 35px; letter-spacing: 10px;">EJERCICIO 2022</th>

                <th colspan="2">FECHA DE INICIO <br>DE ACTIVIDADES</th>

                <th colspan="2" style="font-size: 20px;">{{ date_format( date_create ($business['bussDateStartedAct']),"d/m/Y") }}</th>

            </tr>
            <tr>
                <th rowspan="2">PERIODO</th>
                <th rowspan="2">DETALLE</th>
                <th rowspan="2">BALANCE </th>
                <th colspan="2">ADELANTO</th>

                <th colspan="2">COMPROBANTE</th>
                <th colspan="2">MENSUALIDAD</th>

                <th colspan="2">COMPROBANTE</th>

                <th rowspan="2">RESP. DE<br> COBRO</th>
                <th rowspan="2">FIRMA</th>
            </tr>
            <tr>


                <th>MONTO</th>
                <th>FECHA</th>
                <th>DOC.</th>
                <th>N°</th>
                <th>MONTO</th>
                <th>FECHA</th>
                <th>DOC.</th>
                <th>N°</th>

            </tr>
            @foreach ($d_business_period['serviceProvided'] as $key => $value)
            @if($value->services['svId'] == 1 || $value->services['svId'] == 2)
            <tr>
                <th style="font-weight: lighter;">{{ $value->periodPayments['ppayName'] }}</th>
                <th style="width: 250px; font-weight: lighter;">{{ $value->services['svName'] }}</th>
                <th style="width: 80px; font-weight: lighter;"></th>
                @if(count($value['paymentDetails'])==2)
                @foreach ($value['paymentDetails'] as $key=> $val)
                <th style="width: 60px; font-weight: lighter; text-align: right;">S/&nbsp{{ $val->payments['payTotal'] }}</th>
                <th style="width: 60px; font-weight: lighter;">{{ date_format( date_create ($val->payments['payDatePrint']),"d/m/y") }}</th>
                @if(!($val->payments['payTicketSN'] || $val->payments['payInvoiceSN']))
                <th style="width: 50px; font-weight: lighter;">REC.</th>
                <th style="width: 50px; font-weight: lighter;">{{ sprintf('%05d', $val->payments['payNumber']) }}</th>
                @endif
                @if($val->payments['payTicketSN'])
                <th style="width: 50px; font-weight: lighter;">B/V.</th>
                <th style="width: 50px; font-weight: lighter;">{{ $val->payments['payTicketSN'] }}</th>
                @endif
                @if($val->payments['payInvoiceSN'])
                <th style="width: 50px; font-weight: lighter;">FAC.</th>
                <th style="width: 50px; font-weight: lighter;">{{ $val->payments['payInvoiceSN'] }}</th>
                @endif
                @endforeach
                @elseif(count($value['paymentDetails'])==1)
                <th style="width: 60px; font-weight: lighter;"></th>
                <th style="width: 60px; font-weight: lighter;"></th>
                <th style="width: 50px; font-weight: lighter;"></th>
                <th style="width: 50px; font-weight: lighter;"></th>
                @foreach ($value['paymentDetails'] as $key=> $val)
                <th style="width: 60px; font-weight: lighter; text-align: right;">S/&nbsp{{ $val->payments['payTotal'] }}</th>
                <th style="width: 60px; font-weight: lighter;">{{ date_format( date_create ($val->payments['payDatePrint']),"d/m/y") }}</th>
                @if(!($val->payments['payTicketSN'] || $val->payments['payInvoiceSN']))
                <th style="width: 50px; font-weight: lighter;">REC.</th>
                <th style="width: 50px; font-weight: lighter;">{{ sprintf('%05d', $val->payments['payNumber']) }}</th>
                @endif
                @if($val->payments['payTicketSN'])
                <th style="width: 50px; font-weight: lighter;">B/V.</th>
                <th style="width: 50px; font-weight: lighter;">{{ $val->payments['payTicketSN'] }}</th>
                @endif
                @if($val->payments['payInvoiceSN'])
                <th style="width: 50px; font-weight: lighter;">FAC.</th>
                <th style="width: 50px; font-weight: lighter;">{{ $val->payments['payInvoiceSN'] }}</th>
                @endif
                @endforeach
                @elseif(count($value['paymentDetails'])==0)
                <th style="width: 60px; font-weight: lighter;"></th>
                <th style="width: 60px; font-weight: lighter;"></th>
                <th style="width: 50px; font-weight: lighter;"></th>
                <th style="width: 50px; font-weight: lighter;"></th>
                <th style="width: 50px; font-weight: lighter; text-align: right;">S/&nbsp{{ $value['spCost'] }}</th>
                <th style="width: 50px; font-weight: lighter;"></th>
                <th style="width: 50px; font-weight: lighter;"></th>
                <th style="width: 50px; font-weight: lighter;"></th>
                @endif
                <th style="width: 60px; font-weight: lighter;">{{ strtok($val->payments->user->person['perName'], " ") }}</th>
                <th style="width: 60px; font-weight: lighter;"></th>
            </tr>
            @endif
            @endforeach
        </Table>
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
                    @foreach ($d_business_period['serviceProvided'] as $key => $value)
                    @if(($value->services['svId'] != 1) && ($value->services['svId'] != 2))
                    <tr>
                        <th style="font-weight: lighter;">{{ $value->services['svName'] }}</th>
                        @if(count($value['paymentDetails'])==1)
                        @foreach ($value['paymentDetails'] as $key => $val)
                        <th style="width: 50px; font-weight: lighter;">S/&nbsp{{ $val->payments['payTotal'] }}</th>
                        <th style="width: 50px; font-weight: lighter;">{{ date_format( date_create ($val->payments['payDatePrint']),"d/m/y") }}</th>
                        @if(!($val->payments['payTicketSN'] || $val->payments['payInvoiceSN']))
                        <th style="width: 50px; font-weight: lighter;">REC.</th>
                        <th style="width: 50px; font-weight: lighter;">{{ sprintf('%05d', $val->payments['payNumber']) }}</th>
                        @endif
                        @if($val->payments['payTicketSN'])
                        <th style="width: 50px; font-weight: lighter;">B/V.</th>
                        <th style="width: 50px; font-weight: lighter;">{{ $val->payments['payTicketSN'] }}</th>
                        @endif
                        @if($val->payments['payInvoiceSN'])
                        <th style="width: 50px; font-weight: lighter;">FAC.</th>
                        <th style="width: 50px; font-weight: lighter;">{{ $val->payments['payInvoiceSN'] }}</th>
                        @endif
                        @endforeach
                        @elseif(count($value['paymentDetails'])==0)
                        <th style="width: 50px; font-weight: lighter;">S/&nbsp{{ $value['spCost'] }}</th>
                        <th style="width: 50px; font-weight: lighter;"></th>
                        <th style="width: 50px; font-weight: lighter;"></th>
                        <th style="width: 50px; font-weight: lighter;"></th>
                        @endif
                    </tr>
                    @endif
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
                    <tr>
                        <th style="font-weight: lighter;">-</th>
                        <th style="width: 50px; font-weight: lighter;">-</th>
                        <th style="width: 50px; font-weight: lighter;">-</th>
                        <th style="width: 50px; font-weight: lighter;">-</th>
                        <th style="width: 50px; font-weight: lighter;">-</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
