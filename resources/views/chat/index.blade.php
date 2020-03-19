 <!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('styles')
</head>
<body class="light sidebar-mini sidebar-collapse">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <section id="app-chat">
        <router-view></router-view>
    </section>
    <script type="application/javascript" src="{{asset('js/vue/public-chat-app/app.public.js')}}" ></script>

</body>
