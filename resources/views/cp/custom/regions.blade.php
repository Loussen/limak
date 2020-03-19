<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Regionlar - {{$region->name}}</title>

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
<div class="container"  contenteditable="true">
    <h3>{{$country->name}} - {{$region->name}}</h3>
    <div class="row">
        <div class="col-7">
            <table class="table table-borderless">
                <tbody>

                <tr>
                    <th scope="row">Cəmi çəki</th>
                    <td colspan="2">{{$summaWeight}}kq</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-5">
            <table class="table table-borderless">
                <tbody>
                <tr>
                    <th scope="row">Cəmi say</th>
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
        <th></th>
        <th>Müştəri Kodu</th>
        <th>Ad,Soyad</th>
        <th>Seriya nömrəsi</th>
        <th>Telefon</th>
        <th>Email</th>
        <th>İnvoys nömrəsi</th>
        <th>Yol Tarixi</th>
        <th>Mağaza</th>
        <th>Say</th>
        <th>Məhsul tipi</th>
        <th>Qiymət</th>
    </tr>
    <?php $i=1;?>
    @foreach($invoices as $data)
        <tr>
            <td>{{$i++}}</td>
            <?php
            if($data->client_id>0){
            ?>
            <td>1{{str_pad($data->client_id, 6, "0", STR_PAD_LEFT)}}</td>
            <td>{{$data->client_name}} {{$data->client_surname}}</td>
            <td>{{$data->client_serial_number}}</td>
            <td>{{$data->client_phone}}</td>
            <td>{{$data->client_email}}</td>
            <?php
            }else{
            ?>
            <td>{{$data->uniqid}}</td>
            <td>{{$data->name}} {{$data->surname}}</td>
            <td>{{$data->serial_number}}</td>
            <td>{{$data->phone}}</td>
            <td>{{$data->email}}</td>
            <?php
            }
            ?>
            <td><a  href="https://{{$country->id==1?'tr':'usa'}}.limak.az/print-hawb/{{$data->id}}" target="_blank">{{$data->purchase_no}}</a></td>
            <td>{{date("d-m-Y",strtotime($data->way_date))}}</td>
            <td>{{$data->shop_name}}</td>
            <td>{{$data->quantity}}</td>
            <td>{{$data->product_type_name}}</td>
            <td>{{$data->price}}</td>

        </tr>
    @endforeach
</table>


</body>
</html>