<?php
header("Content-type: text/xml",true);
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
?>
<GoodsInfo>
       @foreach($data as $item)
        <GOODS>
                <TR_NUMBER>{{ $item->waybill  }}</TR_NUMBER>
                <DIRECTION>1</DIRECTION>
                <QUANTITY_OF_GOODS>{{ $item->quantity  }}</QUANTITY_OF_GOODS>
                <WEIGHT_GOODS>{{ $item->weight  }}</WEIGHT_GOODS>
                <INVOYS_PRICE>{{ $item->price * $tryToUsd  }}</INVOYS_PRICE>
                <CURRENCY_TYPE>USD</CURRENCY_TYPE>
                <NAME_OF_GOODS>{{ $item->product_type_name  }}</NAME_OF_GOODS>
                <IDXAL_NAME>{{ $item->name }} {{ $item->surname }}</IDXAL_NAME>
                <IDXAL_ADRESS>{{ $item->address }}, BAKU,AZERBAIJAN</IDXAL_ADRESS>
                <IXRAC_NAME>{{ $item->shop_name }}</IXRAC_NAME>
                <IXRAC_ADRESS></IXRAC_ADRESS>
                <GOODS_TRAFFIC_FR>USD</GOODS_TRAFFIC_FR>
                <GOODS_TRAFFIC_TO>031</GOODS_TRAFFIC_TO>
                <QAIME>{{ $item->waybill }}</QAIME>
        </GOODS>
        @endforeach
</GoodsInfo>