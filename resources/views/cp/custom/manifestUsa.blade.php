<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Manifest</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

</head>
<style>
    .mytable{
        width:98%;
        margin:0 auto;
    }
    .mytable td{
        min-height: 60px;
        /*width:60px !important;*/
        /*text-overflow: ellipsis;*/
        /*white-space: nowrap;*/
        /*overflow: hidden;*/
        width:70px;
    }
</style>
<body>
<?php
    $summa = 0;
    foreach ($invoices as $invoice){
        $summa = number_format((float)$summa + (float)$invoice->price, 2);
    }

?>
<div class="container" contenteditable="true">
    <h3>COMMERCIAL INVOICE</h3>
    <div class="row">
        <div class="col-7">
            <table class="table table-borderless">
                <tbody>
                <tr>
                    <th scope="row">SHIPPER</th>
                    <td>SMR Logistics NY LLC</td>
                </tr>
                <tr>
                    <th scope="row">ADDRESS</th>
                    <td>  900 Port Reading Avenue Unit#B5
                    </td>
                </tr>
                <tr>
                    <th scope="row">TELEPHONE</th>
                    <td colspan="2">1-800 431 5119

                    </td>
                </tr>
                <tr>
                    <th scope="row">TOTAL WEIGHT</th>
                    <td colspan="2">85kq</td>
                </tr>
                <tr>
                    <th scope="row">TOTAL PRICE</th>
                    <td colspan="2">{{$summa}} $</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-5">
            <table class="table table-borderless">
                <tbody>
                <tr>
                    <th scope="row">RECEIVER</th>
                    <td>NAME: LIMAK VM MMC<br> ADDRESS: Baki, Yasamal, Sherifzade kuc, 88C <br> TELEPHONE: +994 12 488 3578</td>
                </tr>
                <tr>
                    <th scope="row">COUNT</th>
                    <td>{{count($invoices)}}</td>
                </tr>
                {{--<tr>--}}
                    {{--<th scope="row"></th>--}}
                    {{--<td>320 Cornell Dr Ste C4 Wilmington, DE 19801</td>--}}
                {{--</tr>--}}
                </tbody>
            </table>
        </div>
    </div>
</div>

<table style="" border="1" class="mytable" contenteditable="true">
        <tr>
{{--
            <th>Way Bill</th>
--}}
            <th>Number</th>
            <th>Purchase no</th>
            <th>User num</th>
            <th>Name</th>
            <th>Personal ID</th>
            <th>Fin Kod</th>
            <th>Invoice price</th>
            <th>Invoice CCY</th>
            <th>Weight</th>
            {{--<th>Description</th>--}}
            <th>Item count</th>
            <th>Shop</th>
            <th>City</th>
            <th>Address</th>
            <th>Mobile</th>
            <th>Track orig</th>
            <th>Birth date</th>
{{--
            <th>Last 30days invoice</th>
--}}
        </tr>
        <?php $i=1;?>

         @foreach($invoices as $data)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$data->purchase_no}}</td>

                {{--
                <td>{{$data->waybill}}</td>
--}}
                <?php
                if($data->client_id>0){
                ?>
                <td>1{{str_pad($data->client_id, 6, "0", STR_PAD_LEFT)}}</td>
                <td>{{$data->client_name}} {{$data->client_surname}}</td>
                <td>{{$data->client_serial_number}}</td>
                <td>{{$data->client_pin}}</td>
                <?php
                }elseif($data->person_id>0){
                    ?>
                    <td>{{$data->person_id}}</td>
                    <td>{{$data->person_name}} {{$data->person_surname}}</td>
                    <td>{{$data->person_serial_number}}</td>
                    <td>{{$data->person_pin}}</td>
                <?php
                }else{
                ?>
                <td>{{$data->uniqid}}</td>
                <td>{{$data->name}} {{$data->surname}}</td>
                <td>{{$data->serial_number}}</td>
                <td>{{$data->pin}}</td>
            <?php
                }
                ?>
                <td>{{number_format($data->price, 2)}}</td>
                <td>USD</td>
                <td>{{$data->weight}}</td>
                <td>{{$data->quantity}}</td>
                <td>{{$data->shop_name}}</td>
                <td>Baku</td>
                <?php
                if($data->client_id>0){
                ?>
                <td>{{$data->client_address}}</td>
                <td>{{$data->client_phone}}</td>
                <?php
                }elseif($data->person_id>0){
                    ?>
                <td>{{$data->person_address}}</td>
                <td>{{$data->person_phone}}</td>
                <?php
                }else{
                ?>
                <td>{{$data->address}}</td>
                <td>{{$data->phone}}</td>
                <?php
                }
                ?>
                <td>{{$data->order_tracking_number}}</td>
                <td>{{$data->birthdate}}</td>
                {{--<td>
                    @if(isset($last30days[$data->user_id]))
                        {{ @$last30days[$data->user_id] }}

                    @endif;

                </td>--}}


            </tr>
            @endforeach

    </table>
</body>
</html>