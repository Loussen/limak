@extends('layouts.front')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/plugins.css')}}">
@endsection
@section('content')
<section id="app-order-via-link">
    <router-view></router-view>
</section>
@endsection
@section('scripts')
    <script>
        window.default_locale = "{{ config('app.locale') }}";
        window.fallback_locale = "{{ config('app.fallback_locale') }}";
        window.messages = @json($message);
    </script>
    <script type="application/javascript" src="{{asset('js/vue/order-via-link/app.js')}}?v={{uniqid()}}" ></script>

@endsection
