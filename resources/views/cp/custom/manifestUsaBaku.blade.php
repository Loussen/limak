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
    $summaWeight = 0;
    foreach ($invoices as $invoice){
        $summaWeight = $summaWeight + number_format($invoice->weight, 2);
    }

?>
<div class="container">
    <h3>COMMERCIAL INVOICE</h3>
    <div class="row">
        <div class="col-7">
            <table class="table table-borderless">
                <tbody>
                <tr>
                    <th scope="row">SHIPPER</th>
                    <td>Express line Corporation c/o Limak VM LLC</td>
                </tr>
                <tr>
                    <th scope="row">ADDRESS</th>
                    <td>  147-31 176th Street
                    </td>
                </tr>
                <tr>
                    <th scope="row">TELEPHONE</th>
                    <td colspan="2">718-995-8101

                    </td>
                </tr>
                <tr>
                    <th scope="row">TOTAL WEIGHT</th>
                    <td colspan="2">{{$summaWeight}}kq</td>
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

<table style="" border="1" class="mytable">
        <tr>
            <th>Fin Kod</th>
{{--
            <th>Way Bill</th>
--}}
            <th>User num</th>
            <th>Name</th>
            <th>Personal ID</th>
            <th>Invoice price</th>
            <th>Invoice CCY</th>
            <th>Weight</th>
            {{--<th>Description</th>--}}
            <th>Item count</th>
            <th>Shop</th>
            <th>Mobile</th>
            <th>City</th>
            <th>Address</th>
            <th>Track orig</th>
            <th>Birth date</th>
            <th>Last 30days invoice</th>
        </tr>
            @foreach($invoices as $data)
            <tr>
                <td>{{$data->pin}}</td>
                {{--
                <td>{{$data->waybill}}</td>
--}}
                <td>{{$data->uniqid}}</td>
                <td>{{$data->name}} {{$data->surname}}</td>
                <td>{{$data->serial_number}}</td>
                <td>{{number_format($data->price, 2)}}</td>
                <td>USD</td>
                <td>{{$data->weight}}</td>
                <td>{{$data->quantity}}</td>
                <td>{{$data->shop_name}}</td>
                <td>{{$data->phone}}</td>
                <td>Baku</td>
                <td>{{$data->address}}</td>
                <td>{{$data->order_tracking_number}}</td>
                <td>{{$data->birthdate}}</td>
                <td>
                    @if(isset($last30days[$data->user_id]))
                        {{ @$last30days[$data->user_id] }}

                    @endif;

                </td>


            </tr>
            @endforeach

    </table>
</body>
</html>