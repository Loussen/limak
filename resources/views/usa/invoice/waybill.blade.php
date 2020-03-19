
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><style>
                @page{
                        margin: 0;}
                .enroot{
                        position:relative;
                }
                .root {
                        padding-top:16mm;
                        width:210mm;
                        height:297mm;
                        top:0mm;
                        left:6mm;

                //background-size: 148mm;



                        position:absolute;
                }
                span {
                        position:absolute;

                        font-weight:bold;
                        font-size:12px;
                }
        </style>
</head><body><div class="enroot">
        <div class="root">
                <span style="top:25mm;left:25mm">{{ date('d') }}</span>
                <span style="top:25mm;left:30mm">{{ date('m') }}</span>
                <span style="top:25mm;left:35mm">{{ date('Y') }}</span>

                <span style="top:32mm;left:25mm"></span>
                <span style="top:39mm;left:25mm">LIMAK INTERNET SERVICES</span>

                <span style="top:46mm;left:25mm; max-width:80mm;word-wrap:normal">28 W Ayre Street</span>
                <span style="top:68mm;left:20mm">Wilmington</span>
                <span style="top:68mm;left:45mm">DE</span>
                <span style="top:68mm;left:57mm">19804</span>

                <span style="top:25mm;left:73mm"></span>
                <span style="top:32mm;left:73mm">(212) 2318700</span>


                <span style="top:82mm;left:16mm">{{ $data->name }} {{ $data->surname }} | {{ $data->uniqid }}</span>
                <span style="top:82mm;left:73mm">{{ $data->phone }}</span>
                <span style="top:90mm;left:16mm"></span>
                <span style="top:97mm;left:16mm; max-width:80mm;word-wrap:normal">
                        {{ $data->address }}</span>
                <span style="top:118mm;left:16mm">Baku</span>
                <span style="top:118mm;left:57mm">AZ1000</span>
                <span style="top:118mm;left:93mm">Azerbaijan</span>



                <span style="font-weight:bold;top:27mm;left:115mm">X</span>

                <span style="top:25mm;left:169mm; height:12mm;width:27mm;border:1px solid #ccc;text-align:center; display:block">
                Shipping price<br>{{ $data->shipping_price }}  USD
                <br>
                Total : {{ number_format(($data->price / $tryToUsd) + (float) $data->shipping_price, 2) }} USD
                </span>
                <span style="font-weight:bold;font-size:14;top:47mm;left:113mm">X</span>
                <span style="top:61mm;left:121mm">1</span>
                <span style="top:61mm;left:128mm">{{ $data->weight }}</span>
                <span style="top:66mm;left:121mm"></span>
                <span style="top:66mm;left:128mm"></span>
                <span style="top:71mm;left:121mm"></span>
                <span style="top:71mm;left:128mm"></span>
                <span style="top:66mm;left:121mm"></span>
                <span style="top:66mm;left:128mm"></span>
                <span style="font-weight:bold;left:138mm;top:81mm">x</span>
                <span style="font-weight:bold;top:79mm;left:178mm">X</span>
                <span style="top:81mm;left:121mm">1</span>
                <span style="top:81mm;left:128mm">{{ $data->weight }}</span>
                <span style="top:89mm;left:118mm">{{ $data->product_type_name }}</span>
                <span style="top:92mm;left:150mm;"></span>
                <span style="top:92mm;left:169mm">USA</span>
                <span style="top:92mm;left:187mm">{{ $data->price }}</span>
                <span style="top:97mm;left:118mm"></span>
                <span style="top:97mm;left:150mm;"></span>
                <span style="top:97mm;left:169mm"></span>
                <span style="top:97mm;left:187mm"></span>
                <span style="top:102mm;left:118mm"></span>
                <span style="top:102mm;left:150mm;"></span>
                <span style="top:102mm;left:169mm"></span>
                <span style="top:102mm;left:187mm"></span>
                <span style="top:109mm;left:187mm">{{ $data->price }}</span>
                <span style="top:108mm;left:118mm">{{ $data->purchase_no  }}</span>
                <span style="top:121mm;left:130mm"></span>
                <span style="font-weight:bold;top:119mm;left:162mm">X</span>
                <span style="font-size:12;top:129mm;left:123mm">MUSA</span>
                <span style="font-size:12;top:133mm;left:123mm">{{date('H:i')}}</span>
                <span style="font-size:12;top:133mm;left:133mm">{{date('d-m-Y')}}</span>
                <span style="font-size:12;top:129mm;left:153mm">{{ $data->name }} {{ $data->surname }} | {{ $data->uniqid }}</span>
                <span style="font-size:12;top:133mm;left:153mm">{{date('H:i')}}</span>
                <span style="font-size:12;top:133mm;left:163mm">{{date('d-m-Y')}}</span>
        </div>

</div>
<script>

        window.onload = function(){
                // window.print();
        }
</script>

</body></html>
