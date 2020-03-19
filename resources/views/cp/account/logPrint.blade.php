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

$file = $account->name."-".$begin_date."-".$end_date.".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");

?>

<table style="" border="1" class="mytable">
    <tr>
        <th></th>
        <th>ID</th>
        <th>Ödəniş məbləği</th>
        <th>Ödənişdən qabaq</th>
        <th>Ödənişdən sonra</th>
        <th>Səbəb</th>
        <th>Admin</th>
        <th>İstifadəçi</th>
        <th>Bank</th>
        <th>Tarix</th>
    </tr>
    <?php $i=1;?>
    @foreach($logs as $data)
        <tr>
            <td> {{$i++}}</td>
            <td>{{$data->id}}</td>
            <td><?php
                if($data->type=='minus') echo '-';
                elseif($data->type == 'plus') echo "+";
                ?>{{ str_replace(".",",",$data->payment)}}</td>
            <td>{{ str_replace(".",",",$data->before_payment) }}</td>
            <td>{{ str_replace(".",",",$data->after_payment) }}</td>
            <td>{{ $data->comment }}</td>
            @if($data->admin) <td>{{ $data->admin->name }} {{ $data->admin->surname }}</td>
            @else <td></td>
            @endif
            @if($data->user) <td>{{ $data->user->name }} {{ $data->user->surname }}</td>
            @else <td></td>
            @endif
            <td>{{$account->name}}</td>
            <td>{{ $data->created_at}}</td>


        </tr>
    @endforeach
</table>


</body>
</html>