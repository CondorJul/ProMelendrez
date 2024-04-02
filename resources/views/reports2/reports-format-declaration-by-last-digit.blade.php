<!--<html>

<head>
    <style type="text/css">
        @page {
            margin: 95px 50px 74px 50px;
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
            bottom: -30px;
            left: 0px;
            right: 0px;

            color: black;
            text-align: center;
        }

        #watermark {
            position: fixed;
            right: 5%;
            bottom: 25%;
            width: 15cm;
            height: 15cm;
            z-index: -1000;
            opacity: 0.2;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .x {
            /*border: 2px solid black;*/
            width: 49%;
        }

        .y {
            /*border: 2px solid black;*/
            width: 49%;
            margin-left: 3px;
        }

        .contenedor {
            margin: 0 auto;
        }

        .contenedor .x,
        .contenedor .y {
            display: inline-block;
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

        .conteiner {
            margin: 0 auto;
        }

        .conteiner .a,
        .conteiner .b {
            display: inline-block;
        }

        .espacio {
            height: 20px;
        }
    </style>
</head>

<body>
    <div id="watermark">
        <img src="<?php echo $pic1 ?>" height="100%" width="100%" />
    </div>
    <header>
        <div class="contenedor">
            <div class="x" style="position: relative; top: 25px;">
                <h1 style="font-size: 20px; font-family: Arial, Helvetica, sans-serif;">{{ $teller['tellName'] ?? ''}}</h1>
            </div>
            <div class="y" style="text-align: right; position: relative; top: 10px;">
                <img src="<?php echo $pic ?>" alt="" width="250px" height="60px">
            </div>
        </div>
    </header>
    <footer>
        <h2 style="text-align: left; font-size: 8px; font-family: Arial, Helvetica, sans-serif; margin-top: 5px; font-weight: lighter;
        ">{{ $date }} <span style="margin-left: 65%; font-size: 9px; font-family: Arial, Helvetica, sans-serif; margin-top: 5px; font-weight: lighter;
        ">
                {{ $teller->user->person['perName'] ?? ''}} </span>
        </h2>
    </footer>
    <main>
        <div style="font-size: 12px; font-family: 'Arial Narrow'">

            <h2 style="text-align: center; font-size: 25px; margin-bottom:5px; font-family: Arial, Helvetica, sans-serif; margin-top: 0px;"> {{$month}} - {{ $period['prdsNameShort'] }}</h2>


            <div class="conteiner" style="font-size: 9px; font-family: 'Arial Narrow'; clear:both; position:relative;">

                <div class="a" style="position:absolute; left:0pt;">
                    @foreach($groupeds as $key =>$value)
                    @if($loop->index < 5) <table style="width: 100%;" border="1" cellspacing="0">

                        <tr>
                            <th colspan="7" style="font-size: 14px; color: white; letter-spacing: 5px; background-color: #CC0101;">{{$value['name']}}</th>
                        </tr>

                        <tr style="background-color: rgb(254 240 9);">
                            <th style="height: 12px;">#</th>
                            <th>EMPRESA</th>
                            <th>ARC</th>
                            <th>REG</th>
                            <th>DCL</th>
                            <th>LIB</th>
                            <th>PL</th>
                        </tr>
                        @foreach($value['values'] as $key => $val)
                        <tr>
                            <th style="font-weight: lighter;">{{$loop->index + 1}}</th>
                            <th style="width: 54%; height: 12px; font-weight: lighter; text-align: left;">{{ $substring($val['bussName']) }}</th>
                            <th style="width: 8%; font-weight: lighter;">{{ $val['bussFileNumber'] }}</th>
                            <th style="width: 8%; font-weight: lighter;"></th>
                            <th style="width: 8%; font-weight: lighter;"></th>
                            <th style="width: 8%; font-weight: lighter;"></th>
                            <th style="width: 8%; font-weight: lighter;"></th>
                        </tr>
                        @endforeach
                        <tr>
                            <th colspan="7" class="espacio" style="border-left-style: hidden; border-right-style: hidden; border-bottom-style: hidden;"></th>
                        </tr>

                        </table>
                        @endif
                        @endforeach
                </div>

                <div class="b" style="margin-left:50%;">
                    @foreach($groupeds as $key =>$value)
                    @if($loop->index > 4 )
                    <table style="width: 100%;" border="1" cellspacing="0">

                        <tr>
                            <th colspan="7" style="font-size: 14px; color: white; letter-spacing: 5px; background-color: #CC0101;">{{$value['name']}}</th>
                        </tr>

                        <tr style="background-color: rgb(254 240 9);">
                            <th style="height: 12px;">#</th>
                            <th>EMPRESA</th>
                            <th>ARC</th>
                            <th>REG</th>
                            <th>DCL</th>
                            <th>LIB</th>
                            <th>PL</th>
                        </tr>
                        @foreach($value['values'] as $key => $val)
                        <tr>
                            <th style="font-weight: lighter;">{{$loop->index + 1}}</th>
                            <th style="width: 54%; height: 12px; font-weight: lighter; text-align: left;">{{ $substring($val['bussName']) }}</th>
                            <th style="width: 8%; font-weight: lighter;">{{ $val['bussFileNumber'] }}</th>
                            <th style="width: 8%; font-weight: lighter;"></th>
                            <th style="width: 8%; font-weight: lighter;"></th>
                            <th style="width: 8%; font-weight: lighter;"></th>
                            <th style="width: 8%; font-weight: lighter;"></th>
                        </tr>
                        @endforeach
                        <tr>
                            <th colspan="7" class="espacio" style="border-left-style: hidden; border-right-style: hidden; border-bottom-style: hidden;"></th>
                        </tr>

                    </table>
                    @endif
                    @endforeach
                </div>
            </div>

        </div>
        <br><br>
    </main>
</body>

</html> -->


<html>

<head>
    <style type="text/css">
        @page {
            margin: 80px 50px 74px 50px;
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
            bottom: -40px;
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
    <h2 style="text-align: right; font-size: 8px; font-family: Arial, Helvetica, sans-serif; margin-top: 5px; font-weight: lighter;
        ">{{ $date }}</h2>
    </footer>
    <main>

        @php($pageBreak=false)
        @foreach($groupeds as $keyln=>$valueln)

            @php($arrayBusinesses=$valueln['values'])

            <!--La primera ves no puede dar salto de pagina, pero despues del if, la condicion se vuelve positiva para dar salto de pagina-->
            @if($pageBreak)
                <!--Permite dar salto de pagina -->
                <div style="page-break-after:always;"></div>
            @endif
            <h2 style="text-align: center; font-size: 25px; margin-bottom:5px; font-family: Arial, Helvetica, sans-serif; margin-top: 0px;">REGISTRO DE DECLARACIÓN MENSUAL GENERAL</h2>
            @php($pageBreak=true)


            <div style="font-size: 10px; font-family: Arial, Helvetica, sans-serif;">
                <table style="width: 100%;" border="1" cellspacing="0">
                    <tr>
                        <th colspan="4" style="font-size: 25px; height:30px; font-weight:bold; color: white; letter-spacing: 5px; background-color: #CC0101;">{{$valueln['name']}}</th>
                        <th colspan="5" style="border-top-style: hidden;"></th>
                        <th colspan="3" style="font-size: 20px;font-weight:bold; letter-spacing: 3px;">{{$valueln['month']}}</th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>NOMBRE / RAZÓN SOCIAL</th>
                        <th>RUC</th>
                        <th>TIPO</th>
                        <th>ARCH</th>
                        <th>RÉGIMEN</th>
                        <th>FECHA</th>
                        <th>TIPO DE <b> LIBRO</th>
                        <th>VENT</th>
                        <th>PDT-621</th>
                        <th>PLAME-601</th>
                        <th>LIBROS</th>
                        
                    </tr>
                    @php($countBuss=0)
                    
                    @foreach($arrayBusinesses as $key =>$value)
                        @php($countBuss++)
                    
                       

                        <tr >
                            <th style="width: 2%; height:16px; font-weight: lighter; text-align: center;">{{$countBuss}}</th>
                            <th style="width: 28%; font-weight: lighter; text-align: left; padding-left: 7px;">{{$value['bussName']}}</th>
                            <th style="width: 6%; font-weight: lighter; text-align: center;">{{$value['bussRUC']}}</th>

                            <th style="width: 4.3%; font-weight: lighter; text-align: center;">{{ $getBussFileKindName($value['bussFileKind'] ?? '') }}</th>
                            <th style="width: 2%; font-weight: lighter; text-align: center;">{{$value['bussFileNumber']}}</th>
                            <th style="width: 4.3%; font-weight: lighter; text-align: center;">{{ $getBussRegimeName($value['bussRegime']?? '') }}</th>

                            <th style="width: 7.3%; font-weight: lighter; text-align: center;">
                                @switch($value['bussState'] ?? '')
                                    @case(1/*Activo*/)
                                        @break
                                    @case(2/*Suspendido*/)
                                        SUSP: {{date('d-m-Y', strtotime($value['bussStateDate']))}}
                                        @break
                                    @case(3/*Retirado*/)

                                        RET: {{date('d-m-Y', strtotime($value['bussStateDate']))}}

                                        @break
                                    @default
                                @endswitch

                            </th>
                            <th style="width: 5%; font-weight: lighter; text-align: center;">{{$getBussKindBookAccName($value['bussKindBookAcc']??'')}}</th>
                            <th style="width: 2.5%; font-weight: lighter; text-align: center; " >{{isset($value['teller'])?$value['teller']['tellCode']:''}}</th>

                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach

    </main>
</body>

</html>
