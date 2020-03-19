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

$file="Sivilar-".date("d.m.Y").".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");




?>

<table style="" border="1" class="mytable">
    <tr>
        <th></th>
        <th>TAŞIMA SENEDİ</th>
        <th>ALICI</th>
        <th>MARKASI</th>
        <th>EŞYA TANIMI</th>
        <th>BRÜT AĞIRLIK</th>
        <th>FATURA BEDELİ</th>
        <th>DÖVİZ</th>
    </tr>
    <?php $i=1;?>
    @foreach($invoices as $data)
        <tr>
            <td>{{$i++}}</td>
            <td>{{$data->purchase_no}}</td>

            <?php
            if($data->client_id>0){
            ?>
            <td>{{$data->client_name}} {{$data->client_surname}}</td>
            <?php
            }else{
            ?>
            <td>{{$data->name}} {{$data->surname}}</td>
            <?php
            }
            ?>
            <td>{{$data->shop_name}}</td>
            <td>{{$data->product_type_name}}</td>
            <td>{{$data->weight}}</td>
            <td>{{str_replace(".",",",round($data->price*$tryToEur,2))}}</td>
            <td>EUR</td>
        </tr>
    @endforeach
</table>


</body>
</html>