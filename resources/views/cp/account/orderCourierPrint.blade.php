<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Kuryer <?php echo date("d-m-Y")?></title>

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
        $admin_name = 'Butun';
        $admin_surname = '';
        if($admin){
            $admin_name = $admin->name;
            $admin_surname = $admin->surname;
        }

$file = $admin_name." ".date("d-m-Y").".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");

?>
<h1 style="text-align: center">{{$admin_name}} {{$admin_surname}}</h1>

<table style="" border="1" class="mytable">
    <tr>
        <th>№</th>
        <th>Tarix</th>
        <th>Ad,Soyad</th>
        <th>Telefon</th>
        <th>Ünvan</th>
        <th>Sayı</th>
        <th>Daşınma <br /> haqqı</th>
        <th>Balansdakı <br />borc</th>
        <th>Kuryer <br /> haqqı</th>
        <th>Cəmi<br /> ödəniş</th>
        <th>Qeyd</th>
    </tr>
    <?php $i=1;?>
    @foreach($orders as $data)
        <tr>
            <td> {{$i++}}</td>
            <td>
                <?php
                echo date("d.m.Y",strtotime($data->created_at));
                ?>
            </td>
            <td>{{$data->name}} {{$data->surname}} {{$data->uniqid}}</td>
            <td>{{$data->phone}}</td>
            <td>{{$data->city}}, {{$data->district}} {{$data->village}}  {{$data->street}}</td>
            <td>{{$data->count}}</td>
            <td>
                <?php
                    if(isset($prices[$data->id])){
                        $data->sum_price = $prices[$data->id];
                    }
                ?>
                {{$price = round($usdToAzn*$data->sum_price,2)}}</td>
            <td>
                <?php
                    if($data->balance<=0){
                        $balance = abs($data->balance);
                    }else{
                        $balance = 0;
                    }
                    echo $balance;
                ?>
            </td>
            <td>
                <?php
                    if($data->is_paid==0){
                        $courier_price = $data->price;
                    }else{
                        $courier_price = 0;
                    }
                    echo $courier_price;
                ?>
            </td>
            <td>{{$courier_price+$balance+$price}}</td>
            <td>{{$data->description}}</td>

        </tr>
    @endforeach
</table>


</body>
</html>