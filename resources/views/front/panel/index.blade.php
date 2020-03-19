@extends('layouts.front')
@section('styles')
    <link rel="stylesheet" href="{{asset('front/css/kabinet.css?v=').time()}}">
    <style type="text/css">

        .look .modal-header h5 {
            display: inline-block;
            float: left;
            color: #333;
            font-size: 24px;
        }

        .look .modal-content {
            width: auto;
            border: none;
            margin: 150px auto;
        }

        .look.addcss {
            display: none !important;
        }

        .look .modal-header .close {
            opacity: 1;
            position: absolute;
            top: -10px;
            width: 32px;
            height: 32px;
            line-height: 32px;
            text-align: center;
            right: -8px;
            box-shadow: 1px 0 4px 3px rgba(0,0,0,.3);
            border-radius: 50%;
            background: #fff;
        }

        .look .modal-header {
            border-bottom: none;
            padding: 0;
            background: transparent;
        }

        .overlay {
            position: fixed;
            width: 100%;
            z-index: 99;
            height: 100%;
            background: rgba(0, 0, 0, .4);
        }

        .look .modal-body h2 {
            font-size: 32px;
            line-height: 32px;
            margin: 0 0 20px;
            color: #333;
            font-weight: bold;
            display: inline-block;
        }

        .look .modal-body p {
            clear: both;
        }

        .copy-line {
            background: #ebebeb;
            border-radius: 3px;
            display: inline-block;
            float: right;
            margin-bottom: 25px;
        }

        .copy-line span {
            font-size: 18px;
        }

        .copy-line .copy {
            background: transparent;
            border: none;
            margin-left: 30px;
        }

        .copy:focus {
            outline: none;
        }

        .copy .fa {
            color: #ee7223;
            font-size: 16px;
        }

        @media (min-width: 1200px) {
            .container {
                max-width: 100% !important;
            }
        }
    </style>
@endsection
@section('content')
    <div id="user-panel">
        <router-view></router-view>
    </div>
    @if( false )
    @if(!Cookie::get('kampaniya'.date('Ymd')))
        <div class="modal look fade in " style="display: block" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <img src="{{asset('/front/img/kampaniya.png')}}" class="img-responsive" alt="kampaniya">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h2>Kampaniya</h2>
                        <div class="copy-line">
                            <span>Promo kodu gotür:    <b id="demo">i_{{ownHash(Auth::user()->id)}}</b></span>
                            <button class="copy"><i class="fa fa-files-o"></i></button>
                        </div>
                        <p> Təbriklər! Şanslı 1000 nəfərdən biri də sən oldun, 12-17 mart tarixlərində etdiyin
                            sifarişlərinin 3 kiloqrama qədərinin çatdırılma qiymətinə 50% endirim qazandın!
                            Sifariş etdiyin zaman sənə göndərilmiş promo kodunu “bəyannamə əlavə et” və ya
                            “sifariş et” bölmələrinə daxil et. Promo kod: <b><span style="color: #f95732;font-size: 16px">i_{{ownHash(Auth::user()->id)}}</span></b>. Promo kod yalniz bir dəfə istifadə edilə bilər.
                            Vaxtını itirmə, sifariş et, kampaniyadan yararlan!</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endif
@endsection
@if( false )
    @if(!Cookie::get('kampaniya'.date('Ymd')))

<div class="overlay"></div>
        @endif
@endif

@section('scripts')
    <script>
        window.default_locale = "{{ config('app.locale') }}";
        window.fallback_locale = "{{ config('app.fallback_locale') }}";
        window.messages = @json($message);
        $('.look .close').click(function () {
            $(this).parents('.look').addClass('addcss');
            $('.overlay').css('display','none');
        });

        $('.copy').click(function () {
            copyToClipboard('#demo')
        });

        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }
    </script>
    <script type="text/javascript" src="{{asset('js/vue/user-panel/app.js?v=').time()}}"></script>
@endsection

