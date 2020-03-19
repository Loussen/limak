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
<div class="container">
    <h3>COMMERCIAL INVOICE</h3>
    <div class="row">
        <div class="col-7">
            <table class="table table-borderless">
                <tbody>
                <tr>
                    <th scope="row">SHIPPER</th>
                    <td>LIMAK INTERNET HIZMETLERI</td>
                </tr>
                <tr>
                    <th scope="row">ADDRESS</th>
                    <td>Halkalı Merkez Mahellesi1. Tuna caddesi. Üzümlü SK. 5/7</td>
                </tr>
                <tr>
                    <th scope="row">TELEPHONE</th>
                    <td colspan="2">130025436067</td>
                </tr>
                <tr>
                    <th scope="row">AIRWAYBILL</th>
                    <td colspan="2">235-IST-72655516</td>
                </tr>
                <tr>
                    <th scope="row">TOTAL WEIGHT</th>
                    <td colspan="2">55kq</td>
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
        <th>Finkod</th>
        <th>Ad Soyad</th>
        <th>Dogum tarixi</th>
        <th>Weight</th>
        <th>Item count</th>
        <th>Invoice price</th>
        <th>Shop</th>
        <th>Məsulun adı</th>
        <th>Address</th>
        <th>Track orig</th>
    </tr>
    @foreach($invoices as $data)
        <tr>
            <td>{{$data->pin}}</td>
            <td>{{$data->name}} {{$data->surname}}</td>
            <td>{{$data->birthdate}}</td>
            <td>{{$data->weight}}</td>
            <td>{{$data->quantity}}</td>
            <td>{{number_format($data->price / $tryToUsd + $data->shipping_price, 2)}}</td>
            <td>{{$data->shop_name}}</td>
            <td>{{$data->product_type_name}}</td>
            <td>{{$data->address}}</td>
            <td>{{$data->order_tracking_number}}</td>
        </tr>
    @endforeach

</table>
</body>
</html>