<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Logs</title>

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
        /*text-overflow: ellipsi s;*/
        /*white-space: nowrap;*/
        /*overflow: hidden;*/
        width:70px;
    }
</style>
<body>
<?php

$file = "fatura-".$fatura_id.".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");

?>

<table style="" border="1" class="mytable">
    <tr>
        <th>Sıra</th>
        <th>Müşteri İsimi,  Soyisimi, Kodu</th>
        <th>Purchase No</th>
        <th>Mağaza</th>
        <th>Ağırlık</th>
        <th>Tarih</th>
        <th>Ürün sayı</th>
        <th>İçerik</th>
        <th>Fatura No</th>
        <th>Fiyat</th>
        <th>Kayit</th>
        <th>Oto</th>
    </tr>
    <?php
    $i=1;?>
    @foreach($data as $value)
{{--
        <tr class="tr_{{$value->id}}" style="{{$value->by_bus==1 ? 'background:#e07f7f;color:white;' : ''}}{{$value->liquid_type==1 ? 'background:#7fbce0;color:white;' : ''}}">
--}}
        <tr class="tr_{{$value->id}}">
            @php($price=$value->quantity*$value->price)
            <td>{{$i++}}</td>
            <td>
                <?php
                if($value->client_id>0 and $value->corporate==1){
                ?>
                {{$value->client_name}} {{$value->client_surname}}
                <b>1{{str_pad($value->client_id,6,"0",STR_PAD_LEFT)}}</b>
                <?php
                }else{
                ?>
                {{$value->name}} {{$value->surname}}
                <b>{{$value->uniqid}}</b>
                <?php
                }
                ?>
            </td>
            <td>{{$value->purchase_no}}</td>
            <td>{{$value->shop_name}}</td>
            <td>{{$value->weight}} kq</td>
            <td>{{date("d.m.Y H:i",(strtotime($value->depo_date)))}}</td>
            <td>{{$value->quantity}}</td>
            {{--<td style="max-width: 220px;overflow: hidden;word-break: break-all;">{{!empty($value->productTypes) ? $value->products->productTypes->name: $value->products->product_type_name}}</td>--}}
            <td style="max-width: 220px;overflow: hidden;word-break: break-all;">{{$value->product_type_name}}</td>
            <td>{{$value->fatura_id}}</td>
            <td>{{str_replace(".",",",$value->price)}}TL</td>
            <td>
                {{ $value->comment }}
            </td>
            <td>
                <?php if($value->by_bus) echo "X"; ?>
            </td>


        </tr>
    @endforeach
</table>


</body>
</html>