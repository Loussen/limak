<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            background: rgb(204,204,204);
        }
        .main {
            background: white;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            padding-top: 160px;
            position: relative;
            width: 21cm;
            height: 29.7cm;
        }
        #contact_details{
            font-weight: bold;
        }
        #contact_details p{
            font-size: 12px;
            margin-left: 90px;
        }
        table{
            margin-left: 50px;
            margin-top: 65px;
        }
        tr td:nth-of-type(2){
            width: 330px;
            word-break: break-all;
        }
        tr td:nth-of-type(1){
            width: 50px;
        }
        tr td:nth-of-type(3) {
            width: 80px;
        }
        tr td:nth-of-type(4){
            width: 80px;
        }
        .total_count_td{
            border-top: 1px solid;
        }
        body, page {
            margin: 0;
            box-shadow: 0;
        }
        tbody{
            font-size: 10px;
        }
        .sum{
            margin-left: 106px;
            font-size: 11px;
            margin-top: 10px;
        }
        .sum p {
            margin: 1px 0;
            font-weight: bold;
        }
        .sum p span:nth-of-type(1) {
            width: 300px;
            display: inline-block;
        }
        .sum p span:nth-of-type(2) {
             display: inline-block;
             width: 120px;
        }
        .sum p span:nth-of-type(3) {
            display: inline-block;
            border-bottom: 3px double;
        }


    </style>
    <title>Document</title>
</head>
<body>
<div class="main">
    <div id="contact_details">
        <p>LIMAK VM MMC</p>
        <p>
            Sebail rayon Lermantov 113/117<br>
            Bakü - AZERBAIJAN
        </p>
        <p>
            TEL: +994 12 505 8797<br>
            TAX ID: 130 568 0061
        </p>
    </div>
    <table>
        <tbody>
        @php($sum_price=0)
        @php($total=0)
        @foreach($data as $value)
            @php($sum_price+=$value->price)
            @php($total+=$value->quantity)
            <tr>
                <td>{{$value->quantity}}</td>
                <td>{{$value->name}}</td>
{{--
                <td>{{$value->price}} TL</td>
--}}
                <td>{{ number_format($value->price/$euro,2)}} &euro;</td>
                <td>{{ number_format($value->price/$euro,2)}} &euro;</td>
           </tr>
        @endforeach
        <tr>
            <td class="total_count_td"><b>{{$total}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <div class="sum">
<?php
             $words=[
             	['','bir', 'iki','üç','dörd','beş','altı','yedi','sekiz','dokuz'],
             	['','on','iyirmi','otuz','kırk','elli','altmış','yetmiş','seksen','doksan'],
             	['','yüz','iki yüz','üç yüz','dörd yüz','beş yüz','altı yüz','yedi yüz','sekiz yüz','dokuz yüz'],
             	['','bin','iki bin','üç bin','dörd bin','beş bin','altı bin','yedi bin','sekiz bin','dokuz bin'],
             	['','on bin','iyirmi bin','otuz bin','kırk bin','elli bin','altmış bin','yetmiş bin','seksen bin','doksan bin'],

             ];
             //echo $sum_price;
        $sum=number_format($sum_price/$euro,2);
//        dd( $sum_price/$euro);

        ?>
                <p><span>YALNIZ: {{ numberToWord( $sum, $words )}}</span><span>Genel toplam</span><span>{{ $sum }}&euro;</span></p>
                <p>KUR: {{ $euro }}</p>
                <p>Brüt ağırlık/Gross wieght</p>
                <p>Net ağırlık/Net wieght</p>
                <p>Koli/Box</p>
    </div>

</div>
</body>
</html>

