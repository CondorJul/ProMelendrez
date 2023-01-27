<html>

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
                <h1 style="font-size: 20px; font-family: Arial, Helvetica, sans-serif;">{{ $teller['tellName']}}</h1>
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
                {{ $teller->user->person['perName'] }} </span>
        </h2>
    </footer>
    <main>
        <div style="font-size: 12px; font-family: 'Arial Narrow'">

            <h2 style="text-align: center; font-size: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: 0px;"> {{$month}} - {{ $period['prdsNameShort'] }}</h2>


            <div class="conteiner" style="font-size: 9px; font-family: 'Arial Narrow'; clear:both; position:relative;">

                <div class="a" style="position:absolute; left:0pt;">
                    @foreach($groupeds as $key =>$value)
                    @if($loop->index < 5) <table style="width: 100%;" border="1" cellspacing="0">

                        <tr>
                            <th colspan="7" style="font-size: 14px; color: white; letter-spacing: 5px; background-color: #CC0101;">{{$value['name']}}</th>
                        </tr>

                        <tr style="background-color: rgb(254 240 9);">
                            <th style="height: 15px;">#</th>
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
                            <th style="width: 54%; height: 15px; font-weight: lighter; text-align: left;">{{ $substring($val['bussName']) }}</th>
                            <th style="width: 8%; font-weight: lighter;">{{ $val['bussFileNumber'] }}</th>
                            <th style="width: 8%; font-weight: lighter;">{{ $val['bussRegime'] }}</th>
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
                            <th style="height: 15px;">#</th>
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
                            <th style="width: 54%; height: 15px; font-weight: lighter; text-align: left;">{{ $substring($val['bussName']) }}</th>
                            <th style="width: 8%; font-weight: lighter;">{{ $val['bussFileNumber'] }}</th>
                            <th style="width: 8%; font-weight: lighter;">{{ $val['bussRegime'] }}</th>
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

</html>
