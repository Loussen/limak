<!DOCTYPE html>
<?php
$site_lang = 'az';
$uri = $_SERVER['REQUEST_URI'];
$az_uri = $uri;
$ru_uri = $uri;

if(substr($uri,0,3)=='/az'){
    $ru_uri = '/ru'.substr($uri,3);
}elseif(substr($uri,0,3)=='/ru'){
    $site_lang = 'ru';
    $az_uri ='/az'.substr($uri,3);
}else{
    $az_uri = '/az'.$uri;
    $ru_uri = '/ru'.$uri;
}

$html_lang = $site_lang;


if($_SERVER["HTTP_X_FORWARDED_FOR"]=='213.172.85.133'){
   /* echo $az_uri."<br />";
    echo $ru_uri."<br />";*/
}

$can_uri = $uri;
if(isset($section_meta) and trim($section_meta)=='news'){
    $can_uri = '/'.$site_lang.'/blog/'.$news->id;
    $az_uri = '/az/blog/'.$news->id;
    $ru_uri = '/ru/blog/'.$news->id;

    $meta["title"] = isset($news->name) ? $news->name : null;
    $meta["photo"] = isset($news->file) ? url(Storage::url($news->file)) : null;
    $meta["description"] = isset($news->name) ? $news->name : null;
    $meta["keywords"] = isset($news->keywords) ? $news->keywords : null;
    //$meta["url"] = route('newsIn', ['id' => $news->id, 'slug' => Illuminate\Support\Str::slug($news->name, '-', str_replace('_', '-', app()->getLocale()))]);

}
?>
<html lang="{{$html_lang}}">
<head>
    <title>{{ isset($meta['title']) ? $meta['title']: 'Limak.az - Türkiyədən alış-verişin ən asan yolu..' }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow">
    <meta name="description" content="{{ isset($meta['description'])? $meta['description'] : '2.0$-dan başlayan qiymətlərlə Türkiyədən çatdırılma xidməti.'}}">
    <meta name="keywords" content="{{ isset($meta['keywords'])? $meta['keywords'] : '' }}" />
    <link rel="canonical" href="https://limak.az{{$can_uri}}">
    <link rel="alternate" href="https://limak.az{{$ru_uri}}" hreflang="ru" />
    <link rel="alternate" href="https://limak.az{{$az_uri}}" hreflang="az" />
    <link rel="sitemap" type="application/xml" title="Sitemap" href="https://www.limak.az/sitemap.xml" />
    <meta property="og:title" content="{{ isset($meta['title']) ? $meta['title']: 'Limak.az - Türkiyədən alış-verişin ən asan yolu..' }}">
    <meta property="og:description" content=" {{isset($meta['description'])? $meta['description'] : '2.0$-dan başlayan qiymətlərlə Türkiyədən çatdırılma xidməti.'}}">
    <meta property="og:image" content="{{ isset($meta["photo"])? url($meta["photo"]) : asset('/front/new/img/limak.png')}}">
    <meta property="og:url" content="https://limak.az{{$_SERVER['REQUEST_URI']}}">
    <meta property="og:image:secure_url" content="{{ isset($meta["photo"])?url($meta["photo"]) : asset('/front/new/img/limak.png')}}" />
    <meta property="og:image:width" content="640" />
    <meta property="og:image:height" content="442" />
    <meta property="og:type" content="website">
    <meta property="fb:app_id" content="2435343563220740"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="1JJjP7SRsh2xsh_VzXO8g6DzUsXW3TpwezOJ4ZmErB0" />
    <link rel="stylesheet" href="{{ asset('front/new/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" href="{{  asset('front/new/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@voerro/vue-tagsinput@1.11.2/dist/style.css">
    <link rel="stylesheet" href="{{  asset('front/new/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/new/css/style.css?')/*.time()*/  }}">
    <link rel="stylesheet" href="{{ asset('front/new/css/panel.css?')/*.time()*/  }}">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-135630111-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-135630111-2');
    </script>

    <script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/389823bd788c05260d7100685/08edc9dc35b946bc406821996.js");</script>
    
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '2230960543785408');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1"
             src="https://www.facebook.com/tr?id=2230960543785408&ev=PageView
&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->


    @yield('styles')
</head>
<body class="{{Lang::getLocale()}}">

@include('.front.components.front-header')

@yield('content')

@include('.front.components.front-footer')
@yield('scripts')

@if(1)
    <script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="1602646a-c3c7-4814-9fe6-76807bc355ac";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>
@endif

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>

<script type="text/javascript" src="{{ asset('front/new/js/bootstrap.min.js')  }}"></script>
<script type="text/javascript" src="{{ asset('front/new/js/bootstrap-input-spinner.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@voerro/vue-tagsinput@1.8.0/dist/voerro-vue-tagsinput.js"></script>
<script type="text/javascript" src="{{ asset('front/new/js/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/new/js/main.js?').time() }}"></script>
<script type="text/javascript" src="{{asset('front/new/js/calculator.js?').time()}}"></script>
</body>
</html>
