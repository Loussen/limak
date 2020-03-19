@extends('layouts/admin')
@section('styles')
    <style type="text/css">
        @media (min-width: 1200px) {
            .container {
                max-width: 100% !important;
            }
        }
    </style>
@endsection
@section('content')
    <div id="courier-container">
        <router-view></router-view>
    </div>
@endsection

@section('scripts')
    <script>
        window.default_locale = "{{ config('app.locale') }}";
        window.fallback_locale = "{{ config('app.fallback_locale') }}";
        window.messages = @json($message);
    </script>

    <script type="text/javascript" src="{{asset('js/vue/courier/app.js')}}"></script>
@endsection
