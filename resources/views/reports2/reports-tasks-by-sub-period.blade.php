<html>

<head>
    <style type="text/css">
        @page {
            margin: 80px 74px 74px 80px;
        }

        header {
            border-bottom: 2px solid #CC0101;
            position: fixed;
            top: -74px;
            left: 0px;
            right: 0px;
        }


        footer {
            border-top: 2px solid #CC0101;
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;

            color: black;
            text-align: center;
        }

        #watermark {
            position: fixed;
            right: 20%;
            bottom: 5%;
            width: 15cm;
            height: 15cm;
            z-index: -1000;
            opacity: 0.3;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .a {
            /*border: 2px solid black;*/
            width: 49%;
        }

        .b {
            /*border: 2px solid black;*/
            width: 50%;
            margin-left: 3px;
        }

        .contenedor {
            margin: 0 auto;
        }

        .contenedor .a,
        .contenedor .b {
            display: inline-block;
        }

        .espacio {
            height: 30px;
        }

        .espacio2 {
            height: 10px;
        }
    </style>
</head>

<body>
    <div id="watermark">
        <img src="<?php echo $pic1 ?>" height="100%" width="100%" />
    </div>
    <header>
        <div style="text-align: right; margin-bottom: 5px;">
            <img src="<?php echo $pic ?>" alt="" width="250px" height="60px">
        </div>
    </header>
    <footer>
        <div style="font-size: 10.8px; line-height: 12px; padding-top: 5px;">EL PRESENTE ES PARA INFORMAR RESPECTO A LOS HONORARIOS POR SERVICIOS QUE NOS ADEUDA HASTA LA FECHA. <br> Ante cualquier duda o consulta, sírvase comunicarse con nosotros al celular <span style="color: red; font-weight: bold;">N° 973896051 / 951415451</span> a los señores: <br> José Luis MELENDRES CÓNDOR y/o Rita Cindy MIRANDA RAMOS, o al correo <span style="color: blue; font-weight: bold;">melendres.auditores@hotmail.com</span></div>
    </footer>
    <main>
        @php($pageBreak=false)
        @foreach($groupeds as $keyln=>$valueln)

            @php($dBusinessPeriods=$valueln['values'])
           
            <!--La primera ves no puede dar salto de pagina, pero despues del if, la condicion se vuelve positiva para dar salto de pagina-->
            @if($pageBreak)
                <!--Permite dar salto de pagina -->
                <div style="page-break-after:always;"></div>
            @endif

            @php($pageBreak=true)


            <div style="font-size: 10px; font-family: Arial, Helvetica, sans-serif;">
                <table style="width: 100%;" border="1" cellspacing="0">
                    <tr>
                        <th colspan="4">{{$valueln['name']}}</th>
                        <th colspan="4"></th>
                        <th colspan="3">{{$valueln['month']}}</th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>NOMBRE / RAZÓN SOCIAL</th>
                        <th>RUC</th>
                        <th>TIPO</th>
                        <th>ARC</th>
                        <th>REGIMEN</th>
                        <th>FECHA</th>
                        <th>TIPO DE <b> LIBRO</th>
                        <th>PDT-621</th>
                        <th>PLME-601</th>
                        <th>LIBRO</th>
                        
                    </tr>
                    @php($countBuss=0)
                    @foreach($dBusinessPeriods as $key =>$value)
                        @php($countBuss++)
                        @php($business=$value['business'])
                        @php($doneByMonths=$value['doneByMonths'])

                        <tr>
                            <th style="width: 2%; font-weight: lighter; text-align: center;">{{$countBuss}}</th>
                            <th style="width: 28%; font-weight: lighter; text-align: left;">{{$business['bussName']}}</th>
                            <th style="width: 7%; font-weight: lighter; text-align: center;">{{$business['bussRUC']}}</th>
                            
                            
                            @foreach($doneByMonths as $keydbm =>$valuedbm)
                                <th>{{ $getBussFileKindName($valuedbm['bussFileKind']) }}</th>
                                <th style="width: 3%; font-weight: lighter; text-align: center;">{{$valuedbm['bussFileNumber']}}</th>
                                <th>{{ $getBussRegimeName($valuedbm['bussRegime'])}}</th>

                                <th style="width: 7%; font-weight: lighter; text-align: center;">
                                    @switch($valuedbm['bussState'])
                                        @case(1/*Activo*/)
                                            @break
                                        @case(2/*Suspendido*/)
                                            SUSP: {{date('d-m-Y', strtotime($valuedbm['bussStateDate']))}}
                                            @break
                                        @case(1/*Retirado*/)
                                        
                                            RET: {{date('d-m-Y', strtotime($valuedbm['bussStateDate']))}}

                                            @break
                                        @default
                                    @endswitch

                                </th>
                                <th style="width: 7%; font-weight: lighter; text-align: center;">{{$getBussKindBookAccName($valuedbm['bussKindBookAcc'])}}</th>
                                <!--Aqui hacermos foreach
                                    tambien si tiene rectificacion, solo se muestra la informacion de esta , 
                                    ahora si no tiene se muestra la información normal


                                    -->
                                @php($dDoneByMonthTasks=$valuedbm['dDoneByMonthTasks'])
                                @foreach($dDoneByMonthTasks as $keyddbmt =>$valueddbmt)
                                
                                    <th style="width: 7%; font-weight: lighter; text-align: center;">
                                        @if($valueddbmt['ddbmtRectified']!=null)
                                            @switch($valueddbmt['ddbmtRectified'])
                                                @case(2/* Cerrado*/)
                                                    {{$getUserName($users, $valueddbmt['ddbmtDoneBy'])}}
                                                    
                                                    @if($valueddbmt['ddbmtAmount']!=null/*Si es null entonces deducimos que es la primera opcion*/)
                                                            {{ ($valueddbmt['ddbmtAmount']>0)?'-M':'-O'}}
                                                        @endif
                                                    @break
                                                @default
                                            @endswitch
                                        @else
                                            @switch($valueddbmt['ddbmtState'] )
                                                @case(5/* Cerrado*/)
                                                    {{$getUserName($users, $valueddbmt['ddbmtDoneBy'])}}
                                                    
                                                        @if($valueddbmt['ddbmtAmount']!=null/*Si es null entonces deducimos que es la primera opcion*/)
                                                            {{ ($valueddbmt['ddbmtAmount']>0)?'-M':'-O'}}
                                                        @endif
                                                    @break

                                                @case(7/*No tiene */)
                                                    NT
                                                    @break
                                                @default
                                                -
                                            @endswitch
                                        @endif
                                    </th>

                                @endforeach
                            @endforeach        
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach

    </main>
</body>

</html>
