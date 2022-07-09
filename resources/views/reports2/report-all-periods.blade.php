<html>

<head>
    <style type="text/css">
    </style>
</head>

<body>
    <div style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
        <div style="text-align: right;">
            <img src="<?php echo $pic ?>" alt="" width="250px">
        </div>
        <h1 style="text-align: center; font-size: 35px; font-family: Arial, Helvetica, sans-serif; text-decoration: underline; margin: 5px;">{{ $business['bussName'] }}</h1>
        <h2 style="text-align: center; font-size: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: 0px;">RUC: {{ $business['bussRUC'] }}</h2>
        <h2 style="text-align: right; font-size: 12px; font-family: Arial, Helvetica, sans-serif; margin-top: 5px;">Cerro de Pasco 08 de Julio de 2022</h2>

        @foreach($d_business_period as $key => $value)
        <table style="width: 100%;" border="1" cellspacing="0">
            <tr>
                <th colspan="8" style="font-size: 20px; letter-spacing: 8px;">EJERCICIO {{ $value->periods['prdsNameShort'] }}</th>
            </tr>
            <tr>
                <th>MESES</th>
                <th>DESCRIPCIÓN</th>
                <th colspan="2">DEUDA</th>

                <th colspan="2">PAGADO</th>

                <th colspan="2">MONTO TOTAL</th>

            </tr>
            @foreach($value['serviceProvided'] as $key=> $val)
            <tr>
                <th style="font-weight: lighter;">{{ $val->periodPayments['ppayName'] }}</th>
                <th style="width: 35%; font-weight: lighter;">{{ $val->services['svName'] }}</th>
                <th style="width: 1px; font-weight: lighter; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 15%; font-weight: lighter; text-align: right;">{{ $val['spDebt'] }}</th>
                <th style="width: 1px; font-weight: lighter; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 15%; font-weight: lighter; text-align: right;">{{ $val['spPaid'] }}</th>
                <th style="width: 1px; font-weight: lighter; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 15%; font-weight: lighter; text-align: right;">{{ $val['spCost'] }}</th>
            </tr>
            @endforeach
            <tr>
                <th colspan="2" style="width: 35%;">TOTAL</th>
                <th style="width: 1px; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 15%; text-align: right;">{{ $value['dbpDebt'] }}</th>
                <th style="width: 1px; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 15%; text-align: right;">{{ $value['dbpPaid'] }}</th>
                <th style="width: 1px; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 15%; text-align: right;">{{ $value['dbpCost'] }}</th>

            </tr>
        </table>
        <br>
        @endforeach

        <table style="width: 100%;" border="1" cellspacing="0">
            <tr>
                <th colspan="7" style="font-size: 20px; letter-spacing: 8px;">RESUMEN GENERAL</th>

            </tr>
            <tr>
                <th>AÑOS</th>
                <th colspan="2">DEUDA</th>

                <th colspan="2">PAGADO</th>

                <th colspan="2">MONTO TOTAL</th>

            </tr>
            @php
            $total = 0;
            $total1 = 0;
            $total2 = 0;
            @endphp
            @foreach($d_business_period as $key => $value)
            @php
            $total += $value['dbpCost'];
            $total1 += $value['dbpPaid'];
            $total2 += $value['dbpDebt'];
            @endphp
            <tr>
                <th style="font-weight: lighter;">{{ $value->periods['prdsNameShort'] }}</th>
                <th style="width: 1px; border-right: 1px solid white; font-weight: lighter;">&nbspS/</th>
                <th style="width: 15%; text-align: right; font-weight: lighter;">{{ $value['dbpDebt'] }}</th>
                <th style="width: 1px; border-right: 1px solid white; font-weight: lighter;">&nbspS/</th>
                <th style="width: 15%; text-align: right; font-weight: lighter;">{{ $value['dbpPaid'] }}</th>
                <th style="width: 1px; border-right: 1px solid white; font-weight: lighter;">&nbspS/</th>
                <th style="width: 15%; text-align: right; font-weight: lighter;">{{ $value['dbpCost'] }}</th>
            </tr>
            @endforeach
            <tr>
                <th>TOTAL</th>
                <th style="width: 1px; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 15%; text-align: right;">{{ number_format($total2, 2, '.', '0')}}</th>
                <th style="width: 1px; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 15%; text-align: right;">{{ number_format($total1, 2, '.', '0')}}</th>
                <th style="width: 1px; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 15%; text-align: right;">{{ number_format($total, 2, '.', '0')}}</th>
            </tr>
        </table>
    </div>
</body>

</html>
