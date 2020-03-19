<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Limak administrator control panel">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('assets/img/basic/favicon.ico')}}" type="image/x-icon">
    <title>Limak control panel</title>
    <link rel="stylesheet" href="{{asset('/admin/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/css/bootstrap-select.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="{{asset('/admin/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/css/main.css')}}">
    {{--<link rel="stylesheet" href="{{asset('assets/css/app.css')}}">--}}
    <style type="text/css">
        input::-webkit-input-placeholder {
            color: #e9ecee !important;
        }
        /*.anbar > thead > tr > th{*/
        /*padding:5px;*/
        /*}*/
        /*.anbar > tr > td {*/
        /*padding: 8px 10px;*/
        /*}*/
        .anbar tr th {
            padding: 8px 10px !important;
        }
        .anbar_body tr td {
            padding: 8px 10px !important;
        }
        .search_result tr td{
            padding: 8px 10px !important;

        }
    </style>
    @yield('styles')
</head>
<body>
<input type="hidden" name="url" value="{{$status}}">

@yield('header')
@yield('content')
<script>
    window.messages = @json($message);
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{asset('/admin/js/bootstrap.min.js')}}"></script>
<script src="{{asset('/admin/js/bootstrap-select.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<script src="{{asset('js/sweetalert/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('/usa/js/main.js?v=').time()}}"></script>
<script src="{{asset('front/js/hawb.js?v=').time()}}"></script>
@yield('scripts')

</body>
</html>