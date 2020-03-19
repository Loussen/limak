<!Doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div style="width: 100%;margin: 0 auto;display: table;">

<!-- Ödeme formunun açılması için gereken HTML kodlar / Başlangıç -->

{{--
    <script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
--}}
    <iframe src="https://www.paytr.com/odeme/guvenli/{{$token}}" id="paytriframe" frameborder="0" scrolling="no"></iframe>
    {{--<script>iFrameResize({log:false,autoResize: false,
            sizeWidth: true},'#paytriframe');</script>--}}
    <script>
        $('#paytriframe').css('min-height','770px');
    </script>
    <!-- Ödeme formunun açılması için gereken HTML kodlar / Bitiş -->

</div>

<br><br>
</body>
</html>
