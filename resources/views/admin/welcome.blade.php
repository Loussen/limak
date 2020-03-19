@extends('layouts.admin')
@section('styles')
    <style>
        @media (min-width: 1200px) {
            .container {
                max-width: 100% !important;
            }
        }
        .box-body tr td {
            text-align: left;
        }
        .table tr th {
            text-align: left;
        }
        .copy-input {
            opacity: 0;
            display: flex;
            height: 1px;
            margin: 0;
            padding: 0;
        }
        .card {
            height: 100%;
        }
        .box-header .col-md-2{
            margin-bottom: 10px;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div style="width: 100%;" class="container">
            <section class="paper-card">
                <div class="row">
                    <div style="text-align: center" class="col-lg-12">
                        <h2 style="color:#256bff;"> İDARƏ PANELİNƏ XOŞ GƏLMİŞSİNİZ </h2>
                        <h3>{{Auth::user()->name}} {{ Auth::user()->surname }}</h3>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.search-container').find('input').each(function(element) {
            $(this).keyup(function(e){
                if(e.keyCode === 13)
                {
                    $(this).trigger("submit");
                }
            });
        });
        function submit(e) {
        }
        function copyText(id) {
            var copyText = document.getElementById(id);
            copyText.select();
            document.execCommand("copy");
        }
    </script>
@endsection
