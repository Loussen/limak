
<style>
    span{
        position: absolute;
        font-size: 14px;
        letter-spacing: 2px;
    }
</style>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name='viewport' content='width=device-width, user-scalable=no' />
    <meta name='format-detection' content='telephone=yes'>
    <meta name='description' content='' />
    <title>Book</title>
</head>
<body style="position:relative;">

        <span style="top:2cm; left:3cm;"><?=date('Y-m-d');?></span>
        <span style="top:2cm; left:11.6cm;">{{ $data->uniqid }}</span>
        <span style="top:2.8cm; left:2.5cm;">Limak Internet Hizmetleri</span>

        <span style="top:3.5cm; left:3cm;">{{ $data->shop_name }}</span>
        <span style="top:2.8cm; left:10cm;">5318964270</span>
        <span style="top:4.1cm; left:2cm; width: 480px;">Halkalı Merkez Mahellesi. Üzümlü SK. 5/7 </span>
        <span style="top:6.3cm; left:1.5cm;">Istanbul</span>
        <span style="top:6.3cm; left:9cm;">34768</span>
        <span style="top:6.3cm; left:14.5cm;">TR</span>



        <span style="top:7.7cm; left:2cm;">{{ $data->name }} {{ $data->surname }}</span>
        <span style="top:7.7cm; left:12cm;">{{ $data->phone }}</span>
        <span style="top:9cm; left:1cm; width: 450px;">{{ $data->address }}</span>
        <span style="top:11.3cm; left:1cm;">Baku</span>
        <span style="top:11.3cm; left:9cm;">AZ1000</span>
        <span style="top:11.3cm; left:14.2cm;">AZ</span>


        <span style="top:13cm; left:0.3cm;">LIMAK INTERNET HIZMETLERI</span>
        <span style="top:13cm; left:9.2cm;"><?=date('Y-m-d');?></span>


        <span style="top:2cm; left: 18.1cm;">X</span>
        <span style="top:4cm; left: 25cm;">X </span>
        <span style="top:5.7cm; left:19cm;">1</span>
        <span style="top:5.7cm; left: 20.3cm;">{{ $data->weight }}</span>
        <span style="top:5.7cm; left: 24cm;">{{ $data->length }}</span>
        <span style="top:5.7cm; left: 26.5cm;">{{ $data->width }} </span>
        <span style="top:5.7cm; left: 28.3cm;">{{ $data->height }}</span>
        <span style="top:5.5cm; left: 30cm;">X</span>

        <span style="top:7.5cm; left:21.5cm;">X</span>
        <span style="top:7.5cm; left:28.7cm;">X</span>

        <span style="top:8.7cm; left:19.8cm;">{{ $data->product_type_name }} </span>
        <span style="top:8.7cm; left:23.8cm;"> </span>
        <span style="top:8.7cm; left:28cm;">TR</span>
        <span style="top:8.7cm; left:30.7cm;">{{ $data->price }} TL</span>
        <span style="top:9.3cm; left:23cm;">Shipping Price</span>
        <span style="top:9.3cm; left:30.7cm;">{{ $data->shipping_price }} TL ({{ 5.5 }} $)</span>
        <span style="top:9.9cm; left:23cm;">Total Value for Customs</span>
        <span style="top:9.9cm; left:30.7cm;">{{ $data->price }} TL </span>


        <span style="top:10.8cm; left:19cm;">{{ $data->uniqid }}</span>
        <span style="top11cm; left:26cm;">X</span>


</body>
</html> 